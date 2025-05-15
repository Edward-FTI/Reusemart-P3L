import React, { useState, useEffect } from "react"
import { GetAllDonasi, UpdateDonasi } from "../Api/apiOwner"

function Owner() {
    const [donasiList, setDonasiList] = useState([]);

    const fetchDonasi = async () => {
        try {
            const data = await GetAllDonasi();
            setDonasiList(data);
        }
        catch (error) {
            console.error("Gagal mengambil data donasi: ", error);
        }
    }

    useEffect(() => {
        fetchDonasi();
    }, []);

    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-3">
                <h2>Data Request Donasi</h2>
            </div>

            <table className="table table-bordered table-hover">
                <thead className="table-light">
                    <tr>
                        <th>No</th>
                        <th>ID Organisasi</th>
                        <th>Status</th>
                        <th>Nama Penitip</th>
                        <th>Jenis Barang</th>
                        <th>Jumlah Barang</th>
                        {/* <th>Aksi</th> */}
                    </tr>
                </thead>

                <tbody>
                    {donasiList.length > 0 ? (
                        donasiList.map((d, index) => (
                            <tr key={d.id}>
                                <td>{index + 1}</td>
                                <td>{d.organisasi?.nama}</td>
                                <td>{d.status}</td>
                                <td>{d.nama_penitip}</td>
                                <td>{d.jenis_barang}</td>
                                <td>{d.jumlah_barang}</td>
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td colSpan="6" className="text-center fs-2">Belum ada data request donasi</td>
                        </tr>
                    )
                    }
                </tbody>
            </table>
        </div>
    );
}

export default Owner