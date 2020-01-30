<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Pengunjung extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->__resTraitConstruct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['user_get']['limit'] = 2; // 500 requests per hour per user/key
        // $this->methods['user_post']['limit'] = 2; // 100 requests per hour per user/key
        // $this->methods['user_update']['limit'] = 2; // 50 requests per hour per user/key
        // $this->methods['user_delete']['limit'] = 2; // 50 requests per hour per user/key
        $this->load->model('pengunjung_m');
    }

    public function user_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $pengunjung = $this->pengunjung_m->getAll();
        } else {
            $pengunjung = $this->pengunjung_m->getById($id);
        }

        if ($pengunjung) {
            $this->response([
                'status' => true,
                'data' => $pengunjung
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Id tidak ditemukan'
            ], 404);
        }
    }

    public function user_post()
    {
        $data = [
            'nama' => $this->post('nama'),
            'alamat' => $this->post('alamat'),
            'hp' => $this->post('hp'),
            'pekerjaan' => $this->post('pekerjaan'),
            'hobi' => $this->post('hobi'),
        ];
        $query = $this->pengunjung_m->add($data);
        if ($query > 0) {
            $this->response([
                'status' => true,
                'message' => 'Data ditambahkan'
            ], 201);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data gagal ditambahkan'
            ], 400);
        }
    }

    public function user_put()
    {
        $id = $this->put('id');
        $data = [
            'nama' => $this->put('nama'),
            'alamat' => $this->put('alamat'),
            'hp' => $this->put('hp'),
            'pekerjaan' => $this->put('pekerjaan'),
            'hobi' => $this->put('hobi'),
        ];
        $query = $this->pengunjung_m->update($data, $id);
        if ($query > 0) {
            $this->response([
                'status' => true,
                'message' => 'Data terupdate'
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data gagal terupdate'
            ], 400);
        }
    }

    public function user_delete()
    {
        $id = $this->delete('id');

        if ($id <= 0) {
            $this->response([
                'status' => false,
                'message' => 'masukkan id'
            ], 400);
        } else {
            $query = $this->pengunjung_m->delete($id);
            if ($query > 0) {
                $this->response([
                    'status'=>true,
                    'id' => $id,
                    'message' => "Data terhapus"
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'id' => $id,
                    'message' => "Id tidak ditemukan"
                ], 400);
            }
        }
    }
}
