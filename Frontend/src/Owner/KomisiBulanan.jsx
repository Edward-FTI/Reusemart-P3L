import React, { useEffect, useState } from "react";
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";
import { cetakPdf } from "../Api/apitransaksi_penjualans";
import { toast } from "sonner";

export default function KomisiBulanan() {
    const [transaksiList, setTransaksiList] = useState([]);

    const fetchTransaksi = async () => {
        try {
            const data = await cetakPdf();
            setTransaksiList(data.data);
        } catch (error) {
            toast.error("Gagal mengambil data transaksi");
            console.log(error);
        }
    };

    useEffect(() => {
        fetchTransaksi();
    }, []);

    const handleDownload = () => {
        const doc = new jsPDF();

        doc.setFontSize(12);
        doc.text("ReUse Mart", 14, 20);
        doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 14, 26);

        doc.setFontSize(14);
        doc.setFont(undefined, 'bold');
        doc.text("LAPORAN KOMISI BULANAN", 14, 36);
        const textWidth = doc.getTextWidth("LAPORAN KOMISI BULANAN");
        doc.setLineWidth(0.5);
        doc.line(14, 37, 14 + textWidth, 37);

        doc.setFont(undefined, 'normal');
        doc.setFontSize(12);

        const now = new Date();
        const bulan = now.toLocaleString('id-ID', { month: 'long' });
        const tahun = now.getFullYear();
        const tanggalCetak = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        doc.text(`Bulan : ${bulan}`, 14, 44);
        doc.text(`Tahun : ${tahun}`, 14, 50);
        doc.text(`Tanggal cetak: ${tanggalCetak}`, 14, 56);


        let totalHargaJual = 0;
        let totalKomisiReuseMart = 0;
        let totalKomisiHunter = 0;
        let totalBonusPenitip = 0;

        const bodyData = transaksiList.map(item => {
            const hargaJual = item.barang.harga_barang;
            const tglMasuk = new Date(item.barang.tgl_penitipan);
            const tglLaku = new Date(item.transaksi.tgl_transaksi);
            const selisihHari = Math.floor((tglLaku - tglMasuk) / (1000 * 60 * 60 * 24));

            let persenKomisi = item.barang.penambahan_durasi > 0 ? 30 : 20;
            let komisiReuseMart = (persenKomisi / 100) * hargaJual;

            let komisiHunter = item.barang.id_hunter ? (5 / 100) * hargaJual : 0;

            let bonusPenitip = selisihHari < 7 ? (10 / 100) * ((20 / 100) * hargaJual) : 0;
            komisiReuseMart -= bonusPenitip;

            // Tambahkan ke total
            totalHargaJual += hargaJual;
            totalKomisiReuseMart += komisiReuseMart;
            totalKomisiHunter += komisiHunter;
            totalBonusPenitip += bonusPenitip;

            return [
                `${item.barang.nama_barang.charAt(0).toUpperCase() || ''}${item.barang.id}`,
                item.barang.nama_barang,
                hargaJual.toLocaleString('id-ID'),
                tglMasuk.toLocaleDateString('id-ID'),
                tglLaku.toLocaleDateString('id-ID'),
                komisiHunter.toLocaleString('id-ID'),
                komisiReuseMart.toLocaleString('id-ID'),
                bonusPenitip.toLocaleString('id-ID')
            ];
        });

        // Tambahkan baris total
        bodyData.push([
            "", "TOTAL",
            totalHargaJual.toLocaleString('id-ID'),
            "", "",
            totalKomisiHunter.toLocaleString('id-ID'),
            totalKomisiReuseMart.toLocaleString('id-ID'),
            totalBonusPenitip.toLocaleString('id-ID')
        ]);


        autoTable(doc, {
            startY: 62,
            head: [[
                "Kode Produk", "Nama Produk", "Harga Jual", "Tanggal Masuk", "Tanggal Laku",
                "Komisi Hunter", "Komisi ReUse Mart", "Bonus Penitip"
            ]],
            body: bodyData,
            styles: {
                fontSize: 10,
                textColor: 0,
                lineColor: [0, 0, 0],
                lineWidth: 0.1
            },
            headStyles: {
                fillColor: [255, 255, 255],
                textColor: 0,
                lineColor: [0, 0, 0],
                lineWidth: 0.1
            },
            theme: 'grid'
        });

        doc.save("laporan-komisi-bulanan.pdf");
    };

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-4 mt-3">
                <h2>Laporan Komisi Bulanan</h2>
                <button
                    className="btn btn-success fs-5"
                    onClick={handleDownload}
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

                        let persenKomisi = 20;
                        if (item.barang.penambahan_durasi > 0) {
                            persenKomisi = 30;
                        }
                        let komisiReuseMart = (persenKomisi / 100) * hargaJual;

                        let komisiHunter = 0;
                        if (item.barang.id_hunter) {
                            komisiHunter = (5 / 100) * hargaJual;
                        }

                        let bonusPenitip = 0;
                        if (selisihHari < 7) {
                            bonusPenitip = (10 / 100) * ((20 / 100) * hargaJual);
                        }
                        komisiReuseMart -= bonusPenitip;

                        return (
                            <tr key={item.id}>
                                <td>{index + 1}</td>
                                <td>{`${item.barang.nama_barang.charAt(0).toUpperCase() || ''}${item.barang.id}`}</td>
                                <td>{item.barang.nama_barang}</td>
                                <td>{hargaJual.toLocaleString('id-ID')}</td>
                                <td>{tglMasuk.toLocaleDateString('id-ID')}</td>
                                <td>{tglLaku.toLocaleDateString('id-ID')}</td>
                                <td>{item.barang.hunter?.nama || '-'}</td>
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