import React, { useEffect, useState } from "react";
import { GetDisiapkan, batal } from "../Api/apiPengiriman";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

const Disiapkan = () => {
  const [transaksiList, setTransaksiList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [batalId, setBatalId] = useState(null);

  const fetchDisiapkan = async () => {
    try {
      const data = await GetDisiapkan();
      setTransaksiList(data);
    } catch (error) {
      alert("Gagal mengambil data transaksi disiapkan");
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const handleBatal = async (id) => {
    if (!window.confirm("Yakin ingin membatalkan transaksi ini?")) return;
    setBatalId(id);
    try {
      await batal(id); // Memanggil backend Laravel: batalkanTransaksiDisiapkan($id)
      alert("Transaksi berhasil dibatalkan dan poin dikembalikan.");
      fetchDisiapkan();
    } catch (error) {
      alert("Gagal membatalkan transaksi.");
      console.error(error);
    } finally {
      setBatalId(null);
    }
  };

  useEffect(() => {
    fetchDisiapkan();
  }, []);

  return (
    <div className="container mt-5 bg-white p-4 rounded shadow">
      <h2 className="mb-4">Transaksi dalam Status Disiapkan</h2>

      {loading ? (
        <div>Loading...</div>
      ) : transaksiList.length === 0 ? (
        <div className="alert alert-info">Tidak ada transaksi disiapkan</div>
      ) : (
        <table className="table table-bordered table-hover">
          <thead className="table-light">
            <tr>
              <th>No</th>
              <th>ID Transaksi</th>
              <th>Tanggal Transaksi</th>
              <th>Total Transaksi</th>
              <th>Status Pengiriman</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            {transaksiList.map((t, i) => (
              <tr key={t.no_transaksi}>
                <td>{i + 1}</td>
                <td>{t.no_transaksi}</td>
                <td>{t.tanggal_transaksi}</td>
                <td>
                  {t.total_transaksi?.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                  })}
                </td>
                <td>
                  <span className="badge bg-warning text-dark">
                    {t.status_pengiriman}
                  </span>
                </td>
                <td>
                  <button
                    className="btn btn-danger btn-sm"
                    onClick={() => handleBatal(t.no_transaksi)}
                    disabled={batalId === t.no_transaksi}
                  >
                    {batalId === t.no_transaksi
                      ? "Membatalkan..."
                      : "Batalkan"}
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
};

export default Disiapkan;
