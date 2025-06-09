import React, { useEffect, useState } from "react";
// import { jsPDF } from "jspdf";
// import autoTable from "jspdf-autotable";
import { cetakPdf } from "../Api/apitransaksi_penjualans";
import { toast } from "sonner";


export default function KomisiBulanan() {
    const [transaksiList, setTransaksiList] = useState([]);

    const fetchTransaksi = async () => {
        try {
            const data = await cetakPdf();
            setTransaksiList(data.data);
        }
        catch (error) {
            toast.error("Gagal mengambil data transaksi");
            console.log(error);
        }
    }

    useEffect(() => {
        // fetchBarang();
        fetchTransaksi();
    }, []);



    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-4 mt-3">
                <h2>Laporan Komisi Bulanan</h2>
                <button
                    className="btn btn-success fs-5"
                >
                    <img src="https://img.icons8.com/?size=100&id=83159&format=png&color=FFFFFF" style={{ width: "30px" }} alt="Download" />
                    Unduh Laporan
                </button>
            </div>
            <table className="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga Jual</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Laku</th>
                        <th>ID Hunter</th>
                        <th>Komisi Hunter</th>
                        <th>Komisi Reuse Mart</th>
                        <th>Bonus Penitip</th>

                    </tr>
                </thead>
                <tbody>
                    {transaksiList.length > 0 ? transaksiList.map((item, index) => {
                        const hargaJual = item.barang.harga_barang;
                        const tglMasuk = new Date(item.barang.tgl_penitipan);
                        const tglLaku = new Date(item.transaksi.tgl_transaksi);
                        const selisihHari = Math.floor((tglLaku - tglMasuk) / (1000 * 60 * 60 * 24));

                        // Komisi Reusemart
                        let persenKomisi = 20;
                        if (item.barang.penambahan_durasi > 0) {
                            persenKomisi = 30;
                        } else if (selisihHari < 7) {
                            persenKomisi = 20;
                        }
                        let komisiReuseMart = (persenKomisi / 100) * hargaJual;

                        // Komisi Hunter: 5% dari komisi reusemart
                        let komisiHunter = 0.05 * komisiReuseMart;

                        // Bonus Penitip: 10% dari komisi reusemart jika laku < 7 hari
                        let bonusPenitip = 0;
                        if (selisihHari < 7) {
                            bonusPenitip = 0.1 * komisiReuseMart;
                        }

                        return (
                            <tr key={item.id}>
                                <td>{index + 1}</td>
                                <td>{`${item.barang.nama_barang.charAt(0).toUpperCase() || ''}${item.barang.id}`}</td>
                                <td>{item.barang.nama_barang}</td>
                                <td>{hargaJual.toLocaleString('id-ID')}</td>
                                <td>{tglMasuk.toLocaleDateString('id-ID')}</td>
                                <td>{tglLaku.toLocaleDateString('id-ID')}</td>
                                <td>{item.barang.id_hunter}</td>
                                <td>{komisiHunter.toLocaleString('id-ID')}</td>
                                <td>{komisiReuseMart.toLocaleString('id-ID')}</td>
                                <td>{bonusPenitip.toLocaleString('id-ID')}</td>
                            </tr>
                        );
                    }) : (
                        <tr>
                            <td colSpan="10" className="text-center fs-5">Belum ada data barang</td>
                        </tr>
                    )}
                </tbody>

            </table>
        </div>
    );
}