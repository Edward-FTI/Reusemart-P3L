import React, { useEffect, useState } from "react";
// import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { toast } from "sonner";
import Select from "react-select";


import {
    GetAllBarang,
    CreateBarang,
    UpdateBarang,
    DeleteBarang
} from "../Api/apiBarang";
import { GetAllKategori } from "../Api/apiKategori";
import { GetAllPenitip } from "../Api/apiPenitip";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const CRUDBarangTitipan = () => {
    const [barangList, setBarangList] = useState([]);

    const [kategoriList, setKategoriList] = useState([]);
    const [penitipList, setPenitipList] = useState([]);

    const [isEdit, setIsEdit] = useState(false);


    const [searchTerm, setSearchTerm] = useState('');
    const [filteredBarangList, setFilteredBarangList] = useState([]);



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
        gambar: '',
        gambar_dua: ''
    })

    // const fetchBarang = async () => {
    //     try {
    //         const data = await GetAllBarang();
    //         setBarangList(data);
    //     } catch (error) {
    //         toast.error("Gagal mengambil data barang");
    //     }
    // };

    const fetchBarang = async () => {
        try {
            const data = await GetAllBarang();
            setBarangList(data);
            setFilteredBarangList(data); // Awalnya tampilkan semua
        } catch (error) {
            toast.error("Gagal mengambil data barang");
        }
    };

    const handleSearch = () => {
        const filtered = barangList.filter(b =>
            b.nama_barang.toLowerCase().includes(searchTerm.toLowerCase())
        );
        setFilteredBarangList(filtered);
    };

    const fetchKategori = async () => {
        try {
            const data = await GetAllKategori();
            setKategoriList(data);
        }
        catch (error) {
            toast.error('Gagal mengambil data kategori')
        }
    }

    const fetchPenitip = async () => {
        try {
            const data = await GetAllPenitip();
            setPenitipList(data);
        }
        catch (error) {
            toast.error('Gagal mengambil data penitip');
        }
    }

    useEffect(() => {
        fetchBarang();
        fetchKategori();
        fetchPenitip();
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
            if ( (isEdit && !dataToSubmit.gambar) && (isEdit && !dataToSubmit.gambar_dua) ) {
                delete dataToSubmit.gambar
                delete dataToSubmit.gambar_dua
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
        setForm({ ...barang, gambar: '', gambar_dua:'' });
        setIsEdit(true);
        const modal = new window.bootstrap.Modal(document.getElementById('formModal'));
        modal.show();
    }

    const handleDelete = async (id) => {
        try {
            await api.delete(`/pegawai/${id}`);
            fetchData(); // refetch data
        } catch (error) {
            console.error("Gagal hapus:", error);
        }
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
            gambar: '',
            gambar:''
        });
        setIsEdit(false);
    }

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2>Data Barang</h2>
                    <form
                        className="d-flex"
                        onSubmit={(e) => {
                            e.preventDefault(); // mencegah reload halaman
                            handleSearch();     // jalankan pencarian
                        }}
                    >
                        <input
                            type="search"
                            name="cari"
                            className="form-control me-2"
                            placeholder="Cari barang..."
                            style={{ width: "250px" }}
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                        <button className="btn btn-outline-primary" type="submit">
                            Cari
                        </button>
                    </form>


                </div>
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
                        <th>Nama Penitip</th>
                        <th>Kategori Barang</th>
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
                    {filteredBarangList.length > 0 ? (
                        filteredBarangList.map((b, index) => (
                            <tr key={b.id}>
                                <td>{index + 1}</td>
                                <td>{b.penitip.nama_penitip}</td>
                                <td>{b.kategori_barang.nama_kategori || "Kategori tidak ditemukan"}</td>
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
                                            if (window.confirm('Yaking ingin menghaus data ini?')) {
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
                            <td colSpan="11" className="text-center fs-2">Belum ada data barang</td>
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
                                <label htmlFor="penitip" className="form-label">Nama Penitip</label>
                                <div className="mb-3">
                                    <Select
                                        name="id_penitip"
                                        options={penitipList.map(p => ({
                                            value: p.id,
                                            label: p.nama_penitip
                                        }))}
                                        value={penitipList
                                            .map(p => ({ value: p.id, label: p.nama_penitip }))
                                            .find(option => option.value === form.id_penitip)}
                                        onChange={(selectedOption) =>
                                            setForm({ ...form, id_penitip: selectedOption?.value || '' })
                                        }
                                        placeholder="Cari penitip..."
                                        isClearable
                                    />
                                </div>


                                <label htmlFor="kategori" className="form-label">
                                    Nama Kategori
                                </label>
                                <div className="mb-3">
                                    <select
                                        name="id_kategori"
                                        className="form-select"
                                        value={form.id_kategori}
                                        onChange={handleChange}
                                        required
                                    >
                                        <option value="" disabled>
                                            Pilih Kategori
                                        </option>
                                        {kategoriList.map((kategori) => (
                                            <option key={kategori.id} value={kategori.id}>
                                                {kategori.nama_kategori}
                                            </option>
                                        ))}
                                    </select>
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
                                        </div>

                                        <label htmlFor="gambar_dua" className="form-label">Gambar 2</label>
                                        <div className="mb-3">
                                            <input
                                                type="file"
                                                name="gambar_dua"
                                                className="form-control"
                                                onChange={handleChange}
                                                required
                                            />
                                        </div>
                                    </>
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
