<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangKeluarJadiModel;
use App\Models\BarangMasukMentahModel;
use Dompdf\Dompdf;

class Laporan extends BaseController
{
    public function barangMasuk()
    {
        return view('modernize/laporan/barangmasuk/index');
    }

    public function cetakLaporanBarangMasuk()
    {
        $barangMasukMentahModel = new BarangMasukMentahModel();
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $data['barangmasukmentah'] = $barangMasukMentahModel->getBarangMasukByDateRange($startDate, $endDate);
        // Format tanggal menjadi tanggal Indonesia
        foreach ($data['barangmasukmentah'] as &$item) {
            $item['tanggal'] = format_tanggal($item['tanggal']);
        }

        // Menghitung Total Jumlah
        $totalJumlah = 0;
        foreach ($data['barangmasukmentah'] as $row) {
            $totalJumlah += (int) $row['jumlah'];
        }
        $data['totalJumlah'] = $totalJumlah;

        // Menghitung Total Harga
        $totalHarga = 0;
        foreach ($data['barangmasukmentah'] as $row) {
            $totalHarga += (int) $row['harga'];
        }
        $data['totalHarga'] = format_rupiah($totalHarga);

        // Format harga menjadi format rupiah
        foreach ($data['barangmasukmentah'] as &$item) {
            $item['harga'] = format_rupiah($item['harga']);
        }

        // Memformat tanggal awal dan akhir
        $data['startDate'] = format_tanggal($startDate);
        $data['endDate'] = format_tanggal($endDate);

        $dompdf = new Dompdf();
        $html = view('modernize/laporan/barangmasuk/cetak', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Laporan Barang Masuk.pdf', ['Attachment' => false]);
    }

    public function barangKeluar()
    {
        return view('modernize/laporan/barangkeluar/index');
    }

    public function cetakLaporanBarangKeluar()
    {
        $barangKeluarJadiModel = new BarangKeluarJadiModel();
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $data['barangkeluarjadi'] = $barangKeluarJadiModel->getBarangKeluarByDateRange($startDate, $endDate);
        // Format tanggal menjadi tanggal Indonesia
        foreach ($data['barangkeluarjadi'] as &$item) {
            $item['tanggal'] = format_tanggal($item['tanggal']);
        }

        // Menghitung Total Jumlah
        $totalJumlah = 0;
        foreach ($data['barangkeluarjadi'] as $row) {
            $totalJumlah += (int) $row['jumlah'];
        }
        $data['totalJumlah'] = $totalJumlah;

        // Menghitung Total Harga
        $totalHarga = 0;
        foreach ($data['barangkeluarjadi'] as $row) {
            $totalHarga += (int) $row['harga'];
        }
        $data['totalHarga'] = format_rupiah($totalHarga);

        // Format harga menjadi format rupiah
        foreach ($data['barangkeluarjadi'] as &$item) {
            $item['harga'] = format_rupiah($item['harga']);
        }

        // Memformat tanggal awal dan akhir
        $data['startDate'] = format_tanggal($startDate);
        $data['endDate'] = format_tanggal($endDate);

        $dompdf = new Dompdf();
        $html = view('modernize/laporan/barangkeluar/cetak', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Laporan Barang Keluar.pdf', ['Attachment' => false]);
    }
}