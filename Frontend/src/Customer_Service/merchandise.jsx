

const Merchandise = () => {


    return (
        <div className="container mt-5 bg-white p-4 rounded shadow">
            <div className="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2>Data Barang</h2>
                </div>
                <div class="mb-3">
                    <label for="statusSelect" className="form-label fw-semibold">Status Pengambilan</label>
                    <select id="statusSelect" className="form-select">
                        <option value="">Semua</option>
                        <option value="belum-diambil">Belum Diambil</option>
                    </select>
                </div>

            </div>

            <table className="table table-bordered table-hover">
                <thead className="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Jenis Merchandise</th>
                        <th>Nama Pegawai</th>
                        <th>Tanggal Pengambilan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Erik</td>
                        <td>Mobil</td>
                        <td>Riko</td>
                        <td>12-07-2025</td>
                        <td>Menunggu</td>
                        <td className="d-flex flex-column">
                            <button
                                className="btn btn-primary"
                            >
                                Input Pengambilan
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div >
    );
};

export default Merchandise;
