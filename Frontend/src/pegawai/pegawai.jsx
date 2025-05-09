import React, { useEffect, useState } from 'react';
import {
    GetAllPegawai,
    CreatePegawai,
    UpdatePegawai,
    DeletePegawai
} from '../Api/apiPegawai';
import 'bootstrap/dist/css/bootstrap.min.css';
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const PegawaiPage = () => {
    const [pegawaiList, setPegawaiList] = useState([]);
    const [form, setForm] = useState({ id: '', nama: '', email: '', password: '', gaji: '', id_jabatan: '' });
    const [isEdit, setIsEdit] = useState(false);

    const fetchPegawai = async () => {
        try {
            const data = await GetAllPegawai();
            setPegawaiList(data);
        } catch (error) {
            alert('Gagal mengambil data pegawai');
        }
    };

    useEffect(() => {
        fetchPegawai();
    }, []);

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            if (isEdit) {
                await UpdatePegawai(form);
                alert('Berhasil mengupdate pegawai');
            } else {
                await CreatePegawai(form);
                alert('Berhasil menambah pegawai');
            }
            resetForm();
            fetchPegawai();
        } catch (error) {
            alert('Gagal menyimpan data pegawai');
        }
    };

    const handleEdit = (pegawai) => {
        setForm({ ...pegawai, password: '' });
        setIsEdit(true);
        const modal = new window.bootstrap.Modal(document.getElementById('formModal'));
        modal.show();
    };

    const resetForm = () => {
        setForm({ id: '', nama: '', email: '', password: '', gaji: '', id_jabatan: '' });
        setIsEdit(false);
    };

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-3">
                <h2>Data Pegawai</h2>
                <button
                    className="btn btn-success"
                    onClick={() => {
                        resetForm();
                        const modal = new window.bootstrap.Modal(document.getElementById('formModal'));
                        modal.show();
                    }}
                >
                    Tambah Data
                </button>
            </div>

            <table className="table table-bordered table-hover">
                <thead className="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Gaji</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {pegawaiList.length > 0 ? (
                        pegawaiList.map((p, index) => (
                            <tr key={p.id}>
                                <td>{index + 1}</td>
                                <td>{p.nama}</td>
                                <td>{p.email}</td>
                                <td>{p.gaji}</td>
                                <td>{p.jabatan?.role || 'Jabatan Tidak Ditemukan'}</td>
                                <td>
                                    <button
                                        className="btn btn-sm btn-warning me-2"
                                        onClick={() => handleEdit(p)}
                                    >
                                        Edit
                                    </button>
                                    <button
                                        className="btn btn-sm btn-danger"
                                        onClick={() => {
                                            if (window.confirm('Yakin hapus data ini?')) {
                                                DeletePegawai(p.id).then(fetchPegawai);
                                            }
                                        }}
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td colSpan="6" className="text-center">Belum ada data pegawai</td>
                        </tr>
                    )}
                </tbody>
            </table>

            {/* Modal */}
            <div className="modal fade" id="formModal" tabIndex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="formModalLabel">
                                {isEdit ? 'Edit Pegawai' : 'Tambah Pegawai'}
                            </h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div className="modal-body">
                            <form onSubmit={handleSubmit}>
                                <label htmlFor="nama" className="form-label">Nama Pegawai</label>
                                <div className="mb-3">
                                    <input
                                        name="nama"
                                        className="form-control"
                                        placeholder="Nama"
                                        value={form.nama}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="email" className="form-label">Email</label>
                                <div className="mb-3">
                                    <input
                                        name="email"
                                        className="form-control"
                                        placeholder="Email"
                                        value={form.email}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>
                                {!isEdit && (
                                    <div className="mb-3">
                                        <input
                                            type="password"
                                            name="password"
                                            className="form-control"
                                            placeholder="Password"
                                            value={form.password}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                )}

                                <label htmlFor="gaji" className="form-label">Gaji</label>
                                <div className="mb-3">
                                    <input
                                        name="gaji"
                                        className="form-control"
                                        placeholder="Gaji"
                                        value={form.gaji}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="jabatan" className="form-label">Jabatan</label>
                                <div className="mb-3">
                                    <input
                                        name="id_jabatan"
                                        className="form-control"
                                        placeholder="ID Jabatan"
                                        value={form.id_jabatan}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>
                                <button type="submit" className="btn btn-primary">
                                    {isEdit ? 'Update' : 'Tambah'}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PegawaiPage;
