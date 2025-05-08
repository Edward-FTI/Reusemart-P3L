import React, { useState, useEffect } from "react";
import { Link } from 'react-router-dom';
import useAxios from "../Api/index.jsx";

const Pegawai = () => {
    const [pegawai, setPegawai] = useState([]);
    const [jabatan, setJabatan] = useState([]);

    // Ambil data pegawai
    useEffect(() => {
        useAxios.get("/pegawai")
            .then((res) => {
                const data = res.data;
                if (Array.isArray(data)) {
                    setPegawai(data);
                } else if (Array.isArray(data.data)) {
                    setPegawai(data.data);
                } else {
                    console.error("Format data tidak sesuai", data);
                    setPegawai([]);
                }
            })
            .catch((err) => {
                console.error("Gagal mengambil data pegawai", err);
            });
    }, []);

    // Ambil data jabatan
    useEffect(() => {
        useAxios.get("/jabatan")  // Sesuaikan dengan endpoint untuk jabatan
            .then((res) => {
                const data = res.data;
                if (Array.isArray(data)) {
                    setJabatan(data);
                } else if (Array.isArray(data.data)) {
                    setJabatan(data.data);
                } else {
                    console.error("Format data jabatan tidak sesuai", data);
                    setJabatan([]);
                }
            })
            .catch((err) => {
                console.error("Gagal mengambil data jabatan", err);
            });
    }, []);

    return (
        <div className="container mt-5 table-container p-3 bg-white">
            <div className="d-flex justify-content-between align-items-center">
                <form action="" method="GET" className="d-flex gap-2">
                    <input type="search" className="form-control" id="search" name="search" autoComplete="false" />
                    <button type="submit" className="btn btn-primary">Cari</button>
                </form>
                <Link to="/pegawai/create" className="btn btn-success">Tambah Data</Link>
            </div>

            <table className="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Pegawai</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Email</th>
                        <th scope="col">Gaji</th>
                    </tr>
                </thead>
                <tbody>
                    {pegawai.map((p, index) => {
                        // Cari nama jabatan berdasarkan id_jabatan
                        const jabatanById = jabatan.find(j => j.id === p.id_jabatan);
                        const jabatanNama = jabatanById ? jabatanById.role : 'Jabatan Tidak Ditemukan';

                        return (
                            <tr key={index}>
                                <td>{index + 1}</td>
                                <td>{p.nama}</td>
                                <td>{jabatanNama}</td>
                                <td>{p.email}</td>
                                <td>{p.gaji}</td>
                            </tr>
                        );
                    })}
                </tbody>
            </table>
        </div>
    );
}

export default Pegawai;
