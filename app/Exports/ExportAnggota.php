<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportAnggota implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::select('id_user', 'nama', 'email', 'NIP', 'jenis_kelamin', 'alamat', 'no_tlp', 'shu')
            ->where('usertype', 'user')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Anggota',
            'Nama',
            'Email',
            'NIP',
            'Jenis Kelamin',
            'Alamat',
            'No. Telpon',
            'Sisa Hasil Usaha',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id_user,
            $user->nama,
            $user->email,
            $user->NIP,
            $user->jenis_kelamin,
            $user->alamat,
            $user->no_tlp,
            $user->shu,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get the number of rows in the collection
        $rowCount = $sheet->getHighestRow();

        // Apply styles to all cells based on the number of rows
        $sheet->getStyle("A1:H{$rowCount}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Apply styles to header row
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F81BD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Set the width of columns to auto size
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}