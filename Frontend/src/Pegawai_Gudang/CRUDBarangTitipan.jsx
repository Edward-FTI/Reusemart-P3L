import React, { useEffect, useState } from "react";
import { GetAllBarang } from "../Api/apiBarang";
import { GetAllKategori } from "../Api/apiKategori";

const CRUDBarangTitipan = () => {
    const [barangList, setBarangList] = useState([]);
    const [kategoriList, setKategoriList] = useState([]);

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

    return (
        <div className="bg-white shadow rounded p-5">
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-2xl font-bold">Data Barang Titipan</h2>
                <button className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Tambah Data
                </button>
            </div>

            <table className="w-full border border-gray-200">
                <thead className="bg-gray-100">
                    <tr>
                        <th className="border px-4 py-2">No</th>
                        <th className="border px-4 py-2">Nama Barang</th>
                        <th className="border px-4 py-2">Penitip</th>
                        <th className="border px-4 py-2">Kategori</th>
                        <th className="border px-4 py-2">Harga</th>
                        <th className="border px-4 py-2">Garansi</th>
                        <th className="border px-4 py-2">Status</th>
                        <th className="border px-4 py-2">Gambar</th>
                        <th className="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {barangList.length > 0 ? (
                        barangList.map((barang, index) => (
                            <tr key={barang.id} className="text-center">
                                <td className="border px-4 py-2">{index + 1}</td>
                                <td className="border px-4 py-2">{barang.nama_barang}</td>
                                <td className="border px-4 py-2">{barang.penitip?.nama || '-'}</td>

                                <td className="border px-4 py-2">{barang.kategori_barang?.nama_kategori || '-'}</td>


                                <td className="border px-4 py-2">Rp {barang.harga_barang}</td>
                                <td className="border px-4 py-2">
                                    {barang.status_garansi === "1" ? "Bergaransi" : "Tidak"}
                                </td>
                                <td className="border px-4 py-2">{barang.status_barang}</td>
                                <td className="border px-4 py-2 text-center">
                                    <img
                                        src={`http://localhost:8000/${barang.gambar}`}
                                        alt={barang.nama_barang}
                                        className="img-thumbnail"
                                        style={{ width: "100px", height: "100px", objectFit: "cover" }}
                                    />
                                </td>

                                <td className="border px-4 py-2 text-sm">
                                    {/* Aksi tombol (edit/hapus) bisa ditambahkan di sini */}
                                    <button className="bg-blue-500 text-white px-2 py-1 rounded mr-1">Edit</button>
                                    <button className="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                                </td>
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td colSpan="8" className="border px-4 py-3 text-center text-gray-500">
                                Belum ada data barang
                            </td>
                        </tr>
                    )}
                </tbody>
            </table>
        </div>
    );
};

export default CRUDBarangTitipan;
