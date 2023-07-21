<?php

namespace App\Controllers;

use App\Models\SupplierModel;

class SupplierView extends BaseController
{
    public function index()
    {
        $model = new SupplierModel();

        // Membuat ID Supplier
        $lastSupplier = $model->getLastSupplier();
        $idSupplier = 'S001'; // Nilai default jika tidak ada ID supplier sebelumnya

        if (!empty($lastSupplier)) {
            $lastIdNumber = (int) substr($lastSupplier['idSupplier'], 1);
            $availableIDs = [];

            // Mencari ID supplier yang ada
            for ($i = 1; $i <= $lastIdNumber; $i++) {
                $checkID = 'S' . str_pad($i, 3, '0', STR_PAD_LEFT);
                $existingSupplier = $model->getSupplier($checkID);
                if ($existingSupplier) {
                    $availableIDs[] = $i;
                }
            }

            // Mencari ID supplier yang terlewat
            $missedIDs = array_diff(range(1, $lastIdNumber), $availableIDs);
            if (count($missedIDs) > 0) {
                // Jika ada ID yang terlewat, gunakan ID terlewat terkecil sebagai ID supplier berikutnya
                $nextIdNumber = min($missedIDs);
            } else {
                // Jika tidak ada ID yang terlewat, gunakan ID supplier terakhir + 1
                $nextIdNumber = $lastIdNumber + 1;
            }

            // Format angka menjadi tiga digit dengan awalan nol jika perlu
            $idSupplier = 'S' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);
        }

        $data['idSupplier'] = $idSupplier;
        $data['supplier'] = $model->getSupplier();

        echo view('modernize/master/supplier/index', $data);
    }

    public function input()
    {
        $model = new SupplierModel();
        $lastSupplier = $model->getLastSupplier();

        $idSupplier = 'S001'; // Nilai default jika tidak ada ID supplier sebelumnya

        if (!empty($lastSupplier)) {
            $lastIdNumber = (int) substr($lastSupplier['idSupplier'], 1);
            $availableIDs = [];

            // Mencari ID supplier yang ada
            for ($i = 1; $i <= $lastIdNumber; $i++) {
                $checkID = 'S' . str_pad($i, 3, '0', STR_PAD_LEFT);
                $existingSupplier = $model->getSupplier($checkID);
                if ($existingSupplier) {
                    $availableIDs[] = $i;
                }
            }

            // Mencari ID supplier yang terlewat
            $missedIDs = array_diff(range(1, $lastIdNumber), $availableIDs);
            if (count($missedIDs) > 0) {
                // Jika ada ID yang terlewat, gunakan ID terlewat terkecil sebagai ID supplier berikutnya
                $nextIdNumber = min($missedIDs);
            } else {
                // Jika tidak ada ID yang terlewat, gunakan ID supplier terakhir + 1
                $nextIdNumber = $lastIdNumber + 1;
            }

            // Format angka menjadi tiga digit dengan awalan nol jika perlu
            $idSupplier = 'S' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);
        }



        return view('modernize/master/supplier/input', ['idSupplier' => $idSupplier]);
    }

    public function store()
    {
        $validation =  \Config\Services::validation();

        $data = array(
            'idSupplier'     => $this->request->getVar('idSupplier'),
            'namaSupplier'     => $this->request->getVar('namaSupplier'),
            'alamat'   => $this->request->getVar('alamat'),
            'kontak'   => $this->request->getVar('kontak'),
            'status' => $this->request->getVar('status'),
        );

        if ($validation->run($data, 'supplier') == FALSE) {
            session()->setFlashdata('inputs', $this->request->getPost());
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to(base_url('supplier/input'))->withInput();
        } else {
            $model = new SupplierModel();
            $simpan = $model->insertSupplier($data);
            if ($simpan) {
                session()->setFlashdata('input', 'Data Supplier berhasil ditambahkan!');
                return redirect()->to(base_url('supplier'));
            }
        }
    }

    public function edit($id)
    {
        $model = new SupplierModel();
        $data['supplier'] = $model->getSupplier($id)->getRowArray();
        echo view('modernize/master/supplier/edit', $data);
    }

    public function update()
    {
        $id = $this->request->getVar('oldIdSupplier');
        $validation = \Config\Services::validation();

        $data = array(
            'idSupplier'     => $this->request->getVar('idSupplier'),
            'namaSupplier'     => $this->request->getVar('namaSupplier'),
            'alamat'   => $this->request->getVar('alamat'),
            'kontak'   => $this->request->getVar('kontak'),
            'status'   => $this->request->getVar('status')
        );

        if ($validation->run($data, 'supplier') == FALSE) {
            session()->setFlashdata('inputs', $this->request->getPost());
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to(base_url('supplier/edit/' . $id))->withInput();
        } else {
            $model = new SupplierModel();
            $ubah = $model->updateSupplier($data, $id);
            if ($ubah) {
                session()->setFlashdata('update', 'Data Supplier berhasil diupdate!');
                return redirect()->to(base_url('supplier'));
            }
        }
    }

    public function delete($id)
    {
        $model = new SupplierModel();
        $hapus = $model->deleteSupplier($id);
        if ($hapus) {
            session()->setFlashdata('delete', 'Data Supplier berhasil dihapus!');
            return redirect()->to(base_url('supplier'));
        }
    }
}