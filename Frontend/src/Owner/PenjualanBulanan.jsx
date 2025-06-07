import * as React from 'react';
import { BarChart } from '@mui/x-charts/BarChart';

export default function PenjualanBulanan() {
    const dataPenjualan = [
        { bulan: 'Januari', jumlah: 207, total: 110250000 },
        { bulan: 'Februari', jumlah: 254, total: 152700000 },
        { bulan: 'Maret', jumlah: 190, total: 98000000 },
        { bulan: 'April', jumlah: 248, total: 148000000 },
        { bulan: 'Mei', jumlah: 230, total: 147000000 },
        { bulan: 'Juni', jumlah: 215, total: 133000000 },
        { bulan: 'Juli', jumlah: 250, total: 148500000 },
        { bulan: 'Agustus', jumlah: 180, total: 112000000 },
        { bulan: 'September', jumlah: 0, total: 0 },
        { bulan: 'Oktober', jumlah: 0, total: 0 },
        { bulan: 'November', jumlah: 0, total: 0 },
        { bulan: 'Desember', jumlah: 0, total: 0 },
    ];

    const totalPenjualan = dataPenjualan.reduce((acc, cur) => acc + cur.total, 0);

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            {/* <div className="mb-4">
                <h2 className="fw-bold">ReUse Mart</h2>
                <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
                <h3 className="mt-4 fw-semibold">LAPORAN PENJUALAN BULANAN</h3>
                <p>Tahun: 2024</p>
                <p>Tanggal cetak: 2 Februari 2025</p>
            </div> */}

            <div className="d-flex justify-content-between align-items-center mb-4 mt-3">
                <h2>Laporan Penjualan Bulanan</h2>
                <button
                    className="btn btn-success fs-5"
                >
                    <img src="https://img.icons8.com/?size=100&id=83159&format=png&color=FFFFFF" style={{ width: "30px" }} alt="Download" />
                    Unduh Laporan
                </button>
            </div>

            <table className="table table-bordered table-hover">
                <thead className="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Barang Terjual</th>
                        <th>Jumlah Penjualan Kotor</th>
                    </tr>
                </thead>
                <tbody>
                    {dataPenjualan.map((item, index) => (
                        <tr key={index}>
                            <td>{item.bulan}</td>
                            <td className="text-center">{item.jumlah || '....'}</td>
                            <td className="text-end">
                                {item.total ? item.total.toLocaleString('id-ID') : '....'}
                            </td>
                        </tr>
                    ))}
                    <tr className="fw-bold table-secondary text-center">
                        <td colSpan={2}>Total</td>
                        <td className="text-end">{totalPenjualan.toLocaleString('id-ID')}</td>
                    </tr>
                </tbody>
            </table>

            <BarChart
                height={300}
                xAxis={[{ data: dataPenjualan.map(d => d.bulan.slice(0, 3)) }]}
                series={[
                    {
                        data: dataPenjualan.map(d => d.total),
                        label: 'Penjualan Kotor',
                        color: '#6C8EF2',
                    },
                ]}
            />
        </div>
    );
}
