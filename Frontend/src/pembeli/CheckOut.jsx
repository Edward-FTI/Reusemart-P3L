import React, { useEffect, useState } from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { GetAllCart } from "../Api/apiCart";
import { GetAllAlamat } from "../Api/apiAlamat";
import { GetpembeliById, GetAllpembeli } from "../Api/apiPembeli"; // Pastikan GetpembeliById diimpor
// import { GetAlltransaksi_penjualan, Gettransaksi_penjualanById, Createtransaksi_penjualan } from "../Api/apitransaksi_penjualans";
import { GetPembeliInfo } from "../Api/apiPembeli";
import axios from "axios";

const OrderForm = () => {
  const [deliveryMethod, setDeliveryMethod] = useState("shipped");
  const [address, setAddress] = useState("");
  const [pointsToRedeem, setPointsToRedeem] = useState(0);
  const [availableAddresses, setAvailableAddresses] = useState([]);
  const [buyerPoints, setBuyerPoints] = useState(0);
  const [cartItems, setCartItems] = useState([]);
  const [selectedCartIds, setSelectedCartIds] = useState([]);
  const [showUpload, setShowUpload] = useState(false);
  const [proof, setProof] = useState(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const token = sessionStorage.getItem("token");

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      const cart = await GetAllCart();
      setCartItems(cart);
      setSelectedCartIds(cart.map((item) => item.id));

      const alamatList = await GetAllAlamat();
      setAvailableAddresses(alamatList.map((a) => a.alamat));

      // --- Perubahan dimulai di sini ---
      // Ambil data pembeli berdasarkan ID untuk mendapatkan poin terbaru
//       const currentPembeli = await GetpembeliById(userId);
//       if (currentPembeli && currentPembeli.point !== undefined) {
//         setBuyerPoints(currentPembeli.point);
//       } else {
//         setBuyerPoints(0); // Set 0 jika tidak ada poin atau pembeli tidak ditemukan
//       }
//       // --- Perubahan berakhir di sini ---

//     } catch (err) {
//       console.error("Failed to fetch data:", err); // Pesan error lebih deskriptif
//       const pembeli = await GetPembeliInfo();
//       setBuyerPoints(pembeli.point || 0);
//     } catch (err) {
//       console.error("Gagal mengambil data:", err);
//       alert("Gagal mengambil data. Silakan coba lagi.");
    }
  };

  const handleInitialSubmit = (e) => {
    e.preventDefault();
    setShowUpload(true);
  };

  const handleFinalSubmit = async (e) => {
    e.preventDefault();
    setIsSubmitting(true);

    const formData = new FormData();
    const metode_pengiriman =
      deliveryMethod === "pickup" ? "diambil" : "diantar";
    formData.append("metode_pengiriman", metode_pengiriman);

    if (metode_pengiriman === "diantar") {
      formData.append("alamat_pengiriman", address);
    } else {
      formData.append("alamat_pengiriman", "");
    }

    formData.append("poin_digunakan", pointsToRedeem);
    formData.append("bukti_pembayaran", proof);
    formData.append("status_pengiriman", "di antar");
    formData.append("status_pembelian", "pending");
    formData.append("verifikasi_pembayaran", false);

    selectedCartIds.forEach((id, i) =>
      formData.append(`selected_cart_ids[${i}]`, id)
    );

    try {
      await axios.post("/api/transaksi-penjualan", formData, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "multipart/form-data",
        },
      });
      alert("Transaksi berhasil!");
      setShowUpload(false);
      setProof(null);
      // Optional: Refresh cart or redirect after successful transaction
    } catch (err) {
      alert(err.response?.data?.message || "Gagal melakukan transaksi.");
    } finally {
      setIsSubmitting(false);
    }
  };

  const totalHarga = cartItems.reduce(
    (acc, item) => acc + (item.barang?.harga_barang || 0),
    0
  );
  const diskon = pointsToRedeem * 10000;
  const totalPembayaran = Math.max(totalHarga - diskon, 0);

  return (
    <div className="container my-5">
      <div className="p-4 shadow rounded bg-light">
        <h2 className="mb-4 text-center">Form Pemesanan</h2>

        {!showUpload ? (
          <form onSubmit={handleInitialSubmit}>
            <div className="mb-3">
              <label className="form-label fw-bold">Metode Pengiriman:</label>
              <div className="form-check form-check-inline">
                <input
                  type="radio"
                  id="shipped"
                  className="form-check-input"
                  value="shipped"
                  checked={deliveryMethod === "shipped"}
                  onChange={() => setDeliveryMethod("shipped")}
                />
                <label htmlFor="shipped" className="form-check-label">
                  Dikirim
                </label>
              </div>
              <div className="form-check form-check-inline">
                <input
                  type="radio"
                  id="pickup"
                  className="form-check-input"
                  value="pickup"
                  checked={deliveryMethod === "pickup"}
                  onChange={() => setDeliveryMethod("pickup")}
                />
                <label htmlFor="pickup" className="form-check-label">
                  Ambil Sendiri
                </label>
              </div>
            </div>

            {deliveryMethod === "shipped" && (
              <div className="mb-3">
                <label htmlFor="addressSelect" className="form-label">
                  Alamat Pengiriman
                </label>
                <select
                  id="addressSelect"
                  className="form-select"
                  required
                  value={address}
                  onChange={(e) => setAddress(e.target.value)}
                >
                  <option value="">Pilih Alamat...</option>
                  {availableAddresses.map((addr, idx) => (
                    <option key={idx} value={addr}>
                      {addr}
                    </option>
                  ))}
                </select>
              </div>
            )}

            <div className="mb-3">
              <label className="form-label">
                Poin Tersedia:{" "}
                <span className="badge bg-info text-dark">{buyerPoints}</span>
              </label>
              <input
                type="number"
                className="form-control"
                value={pointsToRedeem}
                min={0}
                max={buyerPoints}
                onChange={(e) => {
                  const value = parseInt(e.target.value);
                  setPointsToRedeem(
                    isNaN(value) ? 0 : Math.min(value, buyerPoints)
                  );
                }}
              />
            </div>

            <div className="mb-4">
              <h5>Ringkasan Pesanan</h5>
              <ul className="list-group mb-2">
                {cartItems.map((item) => (
                  <li
                    key={item.id}
                    className="list-group-item d-flex justify-content-between"
                  >
                    <span>{item.barang?.nama_barang}</span>
                    <span>
                      Rp{item.barang?.harga_barang?.toLocaleString("id-ID")}
                    </span>
                  </li>
                ))}
              </ul>
              <p>
                Total Harga:{" "}
                <strong>Rp{totalHarga.toLocaleString("id-ID")}</strong>
              </p>
              <p>
                Diskon Poin: <strong>Rp{diskon.toLocaleString("id-ID")}</strong>
              </p>
              <p>
                Total Pembayaran:{" "}
                <strong className="text-danger">
                  Rp{totalPembayaran.toLocaleString("id-ID")}
                </strong>
              </p>
            </div>

            <div className="d-grid">
              <button type="submit" className="btn btn-success btn-lg">
                Bayar Sekarang
              </button>
            </div>
          </form>
        ) : (
          <form onSubmit={handleFinalSubmit}>
            <div className="mb-4">
              <label htmlFor="proof" className="form-label">
                Upload Bukti Pembayaran:
              </label>
              <input
                type="file"
                className="form-control"
                id="proof"
                required
                onChange={(e) => setProof(e.target.files[0])}
              />
            </div>
            <div className="d-grid">
              <button
                type="submit"
                className="btn btn-primary btn-lg"
                disabled={isSubmitting}
              >
                {isSubmitting ? "Mengirim..." : "Konfirmasi Pembayaran"}
              </button>
            </div>
          </form>
        )}
      </div>
    </div>
  );
};

export default OrderForm;