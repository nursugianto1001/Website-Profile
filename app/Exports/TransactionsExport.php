<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $filters;
    protected $totalRows;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Transaction::with(['booking'])
            ->orderBy('transaction_time', 'desc');

        // Apply filters yang sama seperti di controller
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($query) use ($search) {
                        $query->where('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_email', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('transaction_status', $this->filters['status']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('transaction_time', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('transaction_time', '<=', $this->filters['date_to']);
        }

        $collection = $query->get();
        $this->totalRows = $collection->count();
        
        return $collection;
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Order ID',
            'Nama Customer',
            'Tanggal Booking',
            'Status Transaksi',
            'Metode Pembayaran',
            'Channel Pembayaran',
            'Jumlah (Rp)',
            'Waktu Transaksi',
            'Status Payment Booking'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->order_id,
            $transaction->booking->customer_name ?? '-',
            $transaction->booking->booking_date ? $transaction->booking->booking_date->format('d/m/Y') : '-',
            ucfirst($transaction->transaction_status),
            $this->getPaymentMethodDisplay($transaction),
            $this->getPaymentChannelDisplay($transaction),
            $transaction->gross_amount,
            $transaction->transaction_time ? $transaction->transaction_time->format('d/m/Y H:i:s') : '-',
            ucfirst($transaction->booking->payment_status ?? 'pending')
        ];
    }

    /**
     * Tentukan display metode pembayaran berdasarkan booking dan transaction
     */
    private function getPaymentMethodDisplay($transaction)
    {
        if (!$transaction->booking) {
            return 'Unknown';
        }

        // Jika booking method adalah cash (input admin)
        if ($transaction->booking->payment_method === 'cash') {
            return 'Cash (Admin Input)';
        }

        // Jika booking method adalah online
        if ($transaction->booking->payment_method === 'online') {
            // Jika ada payment_type dari Midtrans (sudah bayar)
            if (!empty($transaction->payment_type)) {
                return $this->formatMidtransPaymentType($transaction->payment_type);
            }
            
            // Jika belum ada payment_type (belum bayar atau pending)
            return 'Online Payment';
        }

        return 'Unknown';
    }

    /**
     * Format payment type dari Midtrans menjadi lebih readable
     */
    private function formatMidtransPaymentType($paymentType)
    {
        $formatted = str_replace('_', ' ', $paymentType);
        return ucwords($formatted);
    }

    /**
     * Tentukan display payment channel
     */
    private function getPaymentChannelDisplay($transaction)
    {
        // Jika booking method cash, tidak ada channel
        if ($transaction->booking && $transaction->booking->payment_method === 'cash') {
            return 'Cash';
        }

        // Return payment channel dari Midtrans atau default
        return $transaction->payment_channel ?? 'Online';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB']
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $summaryRow = $highestRow + 2;
                
                // Format kolom jumlah sebagai currency
                $sheet->getStyle('H2:H' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('Rp #,##0');

                // Header Summary
                $sheet->setCellValue('A' . $summaryRow, 'LAPORAN KEUANGAN TRANSAKSI');
                $sheet->mergeCells('A' . $summaryRow . ':J' . $summaryRow);
                $sheet->getStyle('A' . $summaryRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '1F2937']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DBEAFE']
                    ]
                ]);

                // Total Data Transaksi
                $totalDataRow = $summaryRow + 1;
                $sheet->setCellValue('A' . $totalDataRow, 'Total Transaksi:');
                $sheet->setCellValue('B' . $totalDataRow, $this->totalRows . ' transaksi');
                
                // Total Pendapatan
                $totalRevenueRow = $summaryRow + 2;
                $sheet->setCellValue('A' . $totalRevenueRow, 'Total Pendapatan:');
                $sheet->setCellValue('B' . $totalRevenueRow, '=SUM(H2:H' . $highestRow . ')');
                
                // Format total pendapatan
                $sheet->getStyle('B' . $totalRevenueRow)
                    ->getNumberFormat()
                    ->setFormatCode('Rp #,##0');

                // Breakdown berdasarkan status transaksi
                $statusBreakdownRow = $summaryRow + 4;
                $sheet->setCellValue('A' . $statusBreakdownRow, 'BREAKDOWN STATUS TRANSAKSI');
                $sheet->mergeCells('A' . $statusBreakdownRow . ':J' . $statusBreakdownRow);
                $sheet->getStyle('A' . $statusBreakdownRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '374151']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6']
                    ]
                ]);

                // Header untuk breakdown
                $breakdownHeaderRow = $statusBreakdownRow + 1;
                $sheet->setCellValue('A' . $breakdownHeaderRow, 'Status');
                $sheet->setCellValue('B' . $breakdownHeaderRow, 'Jumlah');
                $sheet->setCellValue('C' . $breakdownHeaderRow, 'Total (Rp)');

                // Settlement/Capture (Berhasil)
                $settlementRow = $breakdownHeaderRow + 1;
                $sheet->setCellValue('A' . $settlementRow, 'Settlement/Capture');
                $sheet->setCellValue('B' . $settlementRow, '=COUNTIF(E2:E' . $highestRow . ',"settlement")+COUNTIF(E2:E' . $highestRow . ',"capture")');
                $sheet->setCellValue('C' . $settlementRow, '=SUMIF(E2:E' . $highestRow . ',"settlement",H2:H' . $highestRow . ')+SUMIF(E2:E' . $highestRow . ',"capture",H2:H' . $highestRow . ')');
                
                // Pending
                $pendingRow = $settlementRow + 1;
                $sheet->setCellValue('A' . $pendingRow, 'Pending');
                $sheet->setCellValue('B' . $pendingRow, '=COUNTIF(E2:E' . $highestRow . ',"pending")');
                $sheet->setCellValue('C' . $pendingRow, '=SUMIF(E2:E' . $highestRow . ',"pending",H2:H' . $highestRow . ')');
                
                // Failed
                $failedRow = $pendingRow + 1;
                $sheet->setCellValue('A' . $failedRow, 'Cancel/Deny/Expire');
                $sheet->setCellValue('B' . $failedRow, '=COUNTIF(E2:E' . $highestRow . ',"cancel")+COUNTIF(E2:E' . $highestRow . ',"deny")+COUNTIF(E2:E' . $highestRow . ',"expire")');
                $sheet->setCellValue('C' . $failedRow, '=SUMIF(E2:E' . $highestRow . ',"cancel",H2:H' . $highestRow . ')+SUMIF(E2:E' . $highestRow . ',"deny",H2:H' . $highestRow . ')+SUMIF(E2:E' . $highestRow . ',"expire",H2:H' . $highestRow . ')');

                // Breakdown berdasarkan metode pembayaran
                $paymentBreakdownRow = $failedRow + 2;
                $sheet->setCellValue('A' . $paymentBreakdownRow, 'BREAKDOWN METODE PEMBAYARAN');
                $sheet->mergeCells('A' . $paymentBreakdownRow . ':J' . $paymentBreakdownRow);
                $sheet->getStyle('A' . $paymentBreakdownRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '374151']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6']
                    ]
                ]);

                // Header untuk payment breakdown
                $paymentHeaderRow = $paymentBreakdownRow + 1;
                $sheet->setCellValue('A' . $paymentHeaderRow, 'Metode');
                $sheet->setCellValue('B' . $paymentHeaderRow, 'Jumlah');
                $sheet->setCellValue('C' . $paymentHeaderRow, 'Total (Rp)');

                // Online Payment
                $onlineRow = $paymentHeaderRow + 1;
                $sheet->setCellValue('A' . $onlineRow, 'Online Payment');
                $sheet->setCellValue('B' . $onlineRow, '=COUNTIF(F2:F' . $highestRow . ',"Online Payment")+COUNTIFS(F2:F' . $highestRow . ',"<>Cash (Admin Input)",F2:F' . $highestRow . ',"<>Online Payment")');
                $sheet->setCellValue('C' . $onlineRow, '=SUMIF(F2:F' . $highestRow . ',"Online Payment",H2:H' . $highestRow . ')+SUMIFS(H2:H' . $highestRow . ',F2:F' . $highestRow . ',"<>Cash (Admin Input)",F2:F' . $highestRow . ',"<>Online Payment")');

                // Cash Payment
                $cashRow = $onlineRow + 1;
                $sheet->setCellValue('A' . $cashRow, 'Cash (Admin Input)');
                $sheet->setCellValue('B' . $cashRow, '=COUNTIF(F2:F' . $highestRow . ',"Cash (Admin Input)")');
                $sheet->setCellValue('C' . $cashRow, '=SUMIF(F2:F' . $highestRow . ',"Cash (Admin Input)",H2:H' . $highestRow . ')');

                // Format currency untuk breakdown
                $sheet->getStyle('C' . $settlementRow . ':C' . $cashRow)
                    ->getNumberFormat()
                    ->setFormatCode('Rp #,##0');

                // Style untuk breakdown tables
                $statusBreakdownRange = 'A' . $breakdownHeaderRow . ':C' . $failedRow;
                $paymentBreakdownRange = 'A' . $paymentHeaderRow . ':C' . $cashRow;
                
                foreach([$statusBreakdownRange, $paymentBreakdownRange] as $range) {
                    $sheet->getStyle($range)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '9CA3AF']
                            ]
                        ]
                    ]);
                }

                // Style untuk headers
                foreach([$breakdownHeaderRow, $paymentHeaderRow] as $headerRow) {
                    $sheet->getStyle('A' . $headerRow . ':C' . $headerRow)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E5E7EB']
                        ]
                    ]);
                }

                // Style untuk summary
                $summaryRange = 'A' . $totalDataRow . ':B' . $totalRevenueRow;
                $sheet->getStyle($summaryRange)->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '9CA3AF']
                        ]
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9FAFB']
                    ]
                ]);

                // Auto-fit columns
                foreach(range('A','J') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
