import React from "react";
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";

const data = [
    {
        kode: "R96",
        namaProduk: "Rak Piring Besi",
        idPenitip: "T25",
        namaPenitip: "Adi Sanjaya",
        tanggalMasuk: "20/2/2025",
        perpanjangan: "Tidak",
        idHunter: "P11",
        namaHunter: "Bonita Juwita",
        harga: "300.000",
    },
    {
        kode: "K202",
        namaProduk: "Kipas angin Jumbo",
        idPenitip: "T25",
        namaPenitip: "Adi Sanjaya",
        tanggalMasuk: "21/1/2025",
        perpanjangan: "Ya",
        idHunter: "-",
        namaHunter: "-",
        harga: "550.000",
    },
    {
        kode: "K203",
        namaProduk: "Kulkas 3 Pintu",
        idPenitip: "T14",
        namaPenitip: "Gani Hendrawan",
        tanggalMasuk: "22/2/2025",
        perpanjangan: "Ya",
        idHunter: "-",
        namaHunter: "-",
        harga: "4.000.000",
    },
];


const handleDownloadPDF = () => {
    const pdf = new jsPDF("p", "mm", "a4");
    const marginX = 10;
    const pageWidth = 210;
    const contentWidth = pageWidth - 2 * marginX;
    const startY = 10;
    const totalHeaderHeight = 30; // header + tambahan spasi

    // Header
    pdf.setFont("calibri", "bold");
    pdf.setFontSize(11);
    pdf.text("ReUse Mart", marginX + 2, 15);

    pdf.setFont("calibri", "normal");
    pdf.setFontSize(10);
    pdf.text("Jl. Green Eco Park No. 456 Yogyakarta", marginX + 2, 20);

    const laporanText = "LAPORAN Stok Gudang";
    const xText = marginX + 2;
    const yText = 30;

    pdf.setFont("calibri", "bold");
    pdf.setFontSize(11);
    pdf.text(laporanText, xText, yText);

    const textWidth = pdf.getTextWidth(laporanText);
    pdf.setLineWidth(0.2);
    pdf.line(xText, yText + 1, xText + textWidth, yText + 1);

    pdf.setFont("calibri", "normal");
    pdf.setFontSize(10);
    pdf.text("Tanggal cetak: 2 Februari 2025", marginX + 2, 35);

    // Data tabel
    const tableColumn = [
        "Kode Produk", "Nama Produk", "ID Penitip", "Nama Penitip", "Tanggal Masuk",
        "Perpanjangan", "ID Hunter", "Nama Hunter", "Harga"
    ];

    const tableRows = data.map(item => [
        item.kode,
        item.namaProduk,
        item.idPenitip,
        item.namaPenitip,
        item.tanggalMasuk,
        item.perpanjangan,
        item.idHunter,
        item.namaHunter,
        item.harga,
    ]);

    // Border header
    pdf.setDrawColor(0, 0, 0);
    pdf.setLineWidth(0.1);
    pdf.rect(marginX, startY, contentWidth, totalHeaderHeight);

    // AutoTable
    autoTable(pdf, {
        startY: startY + totalHeaderHeight,
        margin: { left: marginX, right: marginX },
        head: [tableColumn],
        body: tableRows,
        styles: {
            lineColor: [0, 0, 0],
            lineWidth: 0.1,
            fontSize: 9,
            cellPadding: 3,
            fillColor: [255, 255, 255],  // background putih isi data
            textColor: [0, 0, 0],        // teks hitam isi data
        },
        headStyles: {
            fillColor: [255, 255, 255],
            textColor: [0, 0, 0],
            lineColor: [0, 0, 0],
            lineWidth: 0.1,
            fontStyle: "bold",
        },
        theme: "grid",
        tableLineWidth: 0,
        tableLineColor: [0, 0, 0],
    });

    pdf.save("Laporan_Stok_Gudang.pdf");
};




export default function StokGudang() {
    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-4 mt-3">
                <h2>Laporan Stok Gudang</h2>
                <button
                    className="btn btn-success fs-5"
                    onClick={handleDownloadPDF}
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
                        <th>ID Penitip</th>
                        <th>Nama Penitip</th>
                        <th>Tanggal Masuk</th>
                        <th>Perpanjangan</th>
                        <th>ID Hunter</th>
                        <th>Nama Hunter</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    {data.map((item, index) => (
                        <tr key={index}>
                            <td>{index + 1}</td>
                            <td>{item.kode}</td>
                            <td>{item.namaProduk}</td>
                            <td>{item.idPenitip}</td>
                            <td>{item.namaPenitip}</td>
                            <td>{item.tanggalMasuk}</td>
                            <td>{item.perpanjangan}</td>
                            <td>{item.idHunter}</td>
                            <td>{item.namaHunter}</td>
                            <td>{item.harga}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
