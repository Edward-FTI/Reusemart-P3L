import React, { useEffect, useState } from "react";
import {
    GetAllBarang,
    CreateBarang,
    UpdateBarang,
    DeleteBarang
} from "../Api/apiBarang";
import { GetAllKategori } from "../Api/apiKategori";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const CRUDBarangTitipan = () => {
    const [barangList, setBarangList] = useState([]);
    const [kategoriList, setKategoriList] = useState([]);
    const [isEdit, setIsEdit] = useState(false);
    const [form, setForm] = useState({
        id: '',
        id_penitip: '',
        id_kategori: '',
        tgl_penitipan: '',
        nama_barang: '',
        harga_barang: '',
        deskripsi: '',
        status_garansi: '',
        status_barang: '',
        gambar: ''
    })

    const fetchBarang = async () => {
        try {
            const data = await GetAllBarang();
            setBarangList(data);
        } catch (error) {
            console.error("Gagal mengambil data barang:", error);
        }
    };

    const fetchKategori = async () => {
        try {
            const data = await GetAllKategori();
            setKategoriList(data);
        }
        catch (error) {
            alert('Gagal mengambil data barang')
        }
    }

    useEffect(() => {
        fetchBarang();
        fetchKategori();
    }, []);

    const handleChange = (e) => {
        const { name, value, files } = e.target;
        setForm({
            ...form,
            [name]: files ? files[0] : value
        });
    };


    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const dataToSubmit = { ...form };
            if (isEdit && !dataToSubmit.gambar) {
                delete dataToSubmit.gambar
            }

            if (isEdit) {
                await UpdateBarang(dataToSubmit);
                alert('Berhasil update data barang');
            }
            else {
                await CreateBarang(dataToSubmit);
                alert('Berhasil menambahkan data barang');
            }
            resetForm();
            fetchBarang();
        }
        catch (error) {
            alert('Gagal menyimpan data barang');
            console.error(error);
        }
    }

    const handleEdit = (barang) => {
        setForm({ ...barang, gambar: '' });
        setIsEdit(true);
        const modal = new window.bootstrap.Modal(document.getElementById('formModal'));
        modal.show();
    }

    const resetForm = () => {
        setForm({
            id: '',
            id_penitip: '',
            id_kategori: '',
            tgl_penitipan: '',
            nama_barang: '',
            harga_barang: '',
            deskripsi: '',
            status_garansi: '',
            status_barang: '',
            gambar: ''
        });
        setIsEdit(false);
    }

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-3">
                <h2>Data Barang</h2>
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
                        <th>ID Penitip</th>
                        <th>ID Kategori</th>
                        <th>Tanggal Penitipan</th>
                        <th>Nama Barang</th>
                        <th>Harga Barang</th>
                        <th>Deskripsi</th>
                        <th>Status Garansi</th>
                        <th>Status Barang</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    {barangList.length > 0 ? (
                        barangList.map((b, index) => (
                            <tr key={b.id}>
                                <td>{index + 1}</td>
                                <td>{b.id_penitip}</td>
                                <td>{b.id_kategori}</td>
                                <td>{b.tgl_penitipan}</td>
                                <td>{b.nama_barang}</td>
                                <td>{b.harga_barang}</td>
                                <td>{b.deskripsi}</td>
                                <td>{b.status_garansi}</td>
                                <td>{b.status_barang}</td>
                                <td>
                                    <img
                                        src={`http://localhost:8000/${b.gambar}`}
                                        alt={b.nama_barang}
                                        className="img-thumbnail"
                                        style={{ width: "100px", height: "100px", objectFit: "cover" }}
                                    />
                                </td>
                                <td>
                                    <button
                                        className="btn btn-sm btn-warning me-2"
                                        onClick={() => handleEdit(b)}
                                    >
                                        Edit
                                    </button>

                                    <button
                                        className="btn btn-sm btn-danger me-2"
                                        onClick={() => {
                                            if (window.confirm('Yakin hapus data ini?')) {
                                                DeleteBarang(b.id).then(fetchBarang);
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
                            <td colSpan="10" className="text-center fs-2">Belum ada data barang</td>
                        </tr>
                    )
                    }
                </tbody>
            </table>

            <div className="modal fade" id="formModal" tabIndex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="formModalLabel">
                                {isEdit ? 'Edit Barang' : 'Tambah Barang'}
                            </h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div className="modal-body">
                            <form onSubmit={handleSubmit}>
                                <label htmlFor="id_penitip" className="form-label">ID Penitip</label>
                                <div className="mb-3">
                                    <input
                                        type='number'
                                        name="id_penitip"
                                        className="form-control"
                                        placeholder="ID Penitip"
                                        value={form.id_penitip}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="id_kategori" className="form-label">ID Kategori</label>
                                <div className="mb-3">
                                    <input
                                        type='number'
                                        name="id_kategori"
                                        className="form-control"
                                        placeholder="ID Kategori"
                                        value={form.id_kategori}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="tgl_penitipan" className="form-label">Tanggal Penitipan</label>
                                <div className="mb-3">
                                    <input
                                        type='date'
                                        name="tgl_penitipan"
                                        className="form-control"
                                        placeholder="Tanggal Penitipan"
                                        value={form.tgl_penitipan}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="nama_barang" className="form-label">Nama Barang</label>
                                <div className="mb-3">
                                    <input
                                        type='text'
                                        name="nama_barang"
                                        className="form-control"
                                        placeholder="Nama Barang"
                                        value={form.nama_barang}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="harga_barang" className="form-label">Harga Barang</label>
                                <div className="mb-3">
                                    <input
                                        type='number'
                                        name="harga_barang"
                                        className="form-control"
                                        placeholder="Harga Barang"
                                        value={form.harga_barang}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="deskripsi" className="form-label">Deskripsi</label>
                                <div className="mb-3">
                                    <input
                                        type='textarea'
                                        name="deskripsi"
                                        className="form-control"
                                        placeholder="Deskripsi"
                                        value={form.deskripsi}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="status_garansi" className="form-label">Status Garansi</label>
                                <div className="mb-3">
                                    <input
                                        type='text'
                                        name="status_garansi"
                                        className="form-control"
                                        placeholder="Status Garansi"
                                        value={form.status_garansi}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                <label htmlFor="status_barang" className="form-label">Status Barang</label>
                                <div className="mb-3">
                                    <input
                                        type='text'
                                        name="status_barang"
                                        className="form-control"
                                        placeholder="Status Barang"
                                        value={form.status_barang}
                                        onChange={handleChange}
                                        required
                                    />
                                </div>

                                {!isEdit && (
                                    <>
                                        <label htmlFor="gambar" className="form-label">Gambar</label>
                                        <div className="mb-3">
                                            <input
                                                type="file"
                                                name="gambar"
                                                className="form-control"
                                                onChange={handleChange}
                                                required
                                            />
                                        </div></>
                                )}
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

export default CRUDBarangTitipan;
