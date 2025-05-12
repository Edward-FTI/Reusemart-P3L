import React, { useEffect, useState } from 'react';
import {
    GetAllPenitip,
    CreatePenitip,
    UpdatePenitip,
    DeletePenitip
} from '../Api/apiPenitip';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const CRUDPenitip = () => {
    const [PenitipList, setPenitipList] = useState([]);
    const [form, setForm] = useState({ id: '', nama_penitip: '', no_ktp: '', gambar_ktp: '', saldo: '', point: '', email: '', password: '', badge: '' });
    const [isEdit, setIsEdit] = useState(false);

    const fetchPenitip = async () => {
        try {
            const data = await GetAllPenitip();
            setPenitipList(data);
        } catch (error) {
            alert('Gagal mengambil data Penitip');
        }
    };

    useEffect(() => {
        fetchPenitip();
    }, []);

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const dataToSubmit = { ...form };
            if (isEdit && !dataToSubmit.password) {
                delete dataToSubmit.password;
            }

            if (isEdit) {
                await UpdatePenitip(dataToSubmit);
                alert('Berhasil mengupdate Penitip');
            } else {
                await CreatePenitip(dataToSubmit);
                alert('Berhasil menambah Penitip');
            }
            resetForm();
            fetchPenitip();
        } catch (error) {
            alert('Gagal menyimpan data Penitip');
            console.error(error);
        }
    };

    const handleEdit = (Penitip) => {
        setForm({ ...Penitip, password: '' });
        setIsEdit(true);
        const modal = new window.bootstrap.Modal(document.getElementById('formModal'));
        modal.show();
    };

    const resetForm = () => {
        setForm({ id: '', nama_penitip: '', no_ktp: '', gambar_ktp: '', saldo: '', point: '', email: '', password: '', badege: '' });
        setIsEdit(false);
    };

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-3">
                <h2>Data Penitip</h2>
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
                        <th>No KTP</th>
                        <th>Gambar KTP</th>
                        <th>Saldo</th>
                        <th>Point</th>
                        <th>Email</th>
                        <th>Badge</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {PenitipList.length > 0 ? (
                        PenitipList.map((p, index) => (
                            <tr key={p.id}>
                                <td>{index + 1}</td>
                                <td>{p.nama_penitip}</td>
                                <td>{p.no_ktp}</td>
                                <td>{p.gambar_ktp ? <img src={p.gambar_ktp} alt="Gambar KTP" style={{ width: '50px', height: '50px' }} /> : 'Tidak ada gambar'}</td>
                                <td>{p.saldo}</td>
                                <td>{p.point}</td>
                                <td>{p.email}</td>
                                <td>{p.badge}</td>
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
                                                DeletePenitip(p.id).then(fetchPenitip);
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
                            <td colSpan="9" className="text-center">Belum ada data Penitip</td>
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
                                {isEdit ? 'Edit Penitip' : 'Tambah Penitip'}
                            </h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div className="modal-body">
                            <form onSubmit={handleSubmit}>
                                <label htmlFor="nama_penitip" className="form-label">Nama Penitip</label>
                                <div className="mb-3">
                                    <input
                                        name="nama_penitip"
                                        className="form-control"
                                        placeholder="Nama"
                                        value={form.nama_penitip}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="no_ktp" className="form-label">No KTP</label>
                                <div className="mb-3">
                                    <input
                                        name="no_ktp"
                                        className="form-control"
                                        placeholder="No KTP"
                                        value={form.no_ktp}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="gambar_ktp" className="form-label">Gambar KTP</label>
                                <div className="mb-3">
                                    <input
                                        type="file"
                                        name="gambar_ktp"
                                        className="form-control"
                                        accept="image/*"
                                        onChange={(e) => setForm({ ...form, gambar_ktp: e.target.files[0] })}
                                        required={!isEdit}
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
                                        <label htmlFor="password" className="form-label">Password</label>
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

                                <label htmlFor="badge" className="form-label">Badge</label>
                                    <div className="mb-3">
                                        <input
                                            name="badge"
                                            className="form-control"
                                            placeholder="Badge (opsional)"
                                            value={form.badge}
                                            onChange={handleChange}
                                        />
                                    </div>

                                    <label htmlFor="saldo" className="form-label">Saldo</label>
                                    <div className="mb-3">
                                        <input
                                            name="saldo"
                                            type="number"
                                            className="form-control"
                                            placeholder="Saldo (default 0)"
                                            value={form.saldo}
                                            onChange={handleChange}
                                        />
                                </div>
                                
                                <label htmlFor="point" className="form-label">point</label>
                                    <div className="mb-3">
                                        <input
                                            name="point"
                                            type="number"
                                            className="form-control"
                                            placeholder="point (default 0)"
                                            value={form.point}
                                            onChange={handleChange}
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

export default CRUDPenitip;
