<?php

namespace App\Http\Controllers;

use App\Models\Senggol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SenggolController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // get data from table senggols
        $senggols = Senggol::latest()->get();

        //make response JSON
        return response()->json([
            'error' => true,
            'message' => 'Berikut Daftar Pasar Senggol',
            'data'    => $senggols
        ], 200);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        //find senggols by ID
        $senggols = Senggol::findOrfail($id);

        //make response JSON
        return response()->json([
            'error' => true,
            'message' => 'Detail Data Pasar Senggol',
            'data'    => $senggols
        ], 200);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'nik'       => 'required|unique:senggols',
            'nama'      => 'required',
            'alamat'    => 'required',
            'surel'     => 'required'
        ]);

        if ($validator->fails('nik')) {
            return response()->json([
                'error' => false,
                'message' => 'NIK Sudah Terdaftar',
            ], 409);
        }

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $senggols = Senggol::create([
            'nik'       => $request->nik,
            'nama'      => $request->nama,
            'alamat'    => $request->alamat,
            'surel'     => $request->surel
        ]);

        //success save to database
        if ($senggols) {

            return response()->json([
                'error' => true,
                'message' => 'Data Pasar Senggol Berhasil di Simpan',
                'data'    => $senggols
            ], 201);
        }

        //failed save to database
        return response()->json([
            'error' => false,
            'message' => 'Data Pasar Senggol Gagal di Simpan',
        ], 409);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $senggols
     * @return void
     */
    public function update(Request $request, Senggol $senggol)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'nik'       => 'required',
            'nama'      => 'required',
            'alamat'    => 'required',
            'surel'     => 'required'
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find senggols by ID
        $senggol = Senggol::findOrFail($senggol->id);

        if ($senggol) {

            //update senggols
            $senggol->update([
                'nik'       => $request->nik,
                'nama'      => $request->nama,
                'alamat'    => $request->alamat,
                'surel'     => $request->surel
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Data Pasar Senggol Berhasil di Edit',
                'data'    => $senggol
            ], 200);
        }

        //data senggols not found
        return response()->json([
            'error' => false,
            'message' => 'Data Pasar Senggol Tidak di Temukan',
        ], 404);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //find senggols by ID
        $senggols = Senggol::findOrfail($id);

        if ($senggols) {

            //delete senggols
            $senggols->delete();

            return response()->json([
                'error' => true,
                'message' => 'Data Pasar Senggol Berhasil di Hapus',
            ], 200);
        }

        //data senggols not found
        return response()->json([
            'error' => false,
            'message' => 'Data Pasar Senggol Tidak di Temukan',
        ], 404);
    }
}
