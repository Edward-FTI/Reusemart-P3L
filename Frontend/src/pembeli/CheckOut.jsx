import React, { useEffect, useState } from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { GetAllCart } from "../Api/apiCart";
import { GetAllAlamat } from "../Api/apiAlamat";
import { GetpembeliById, GetAllpembeli } from "../Api/apiPembeli";
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
  const [pembeli, setPembeli] = useState([]);

  const token = sessionStorage.getItem("token");
  const userId = sessionStorage.getItem("id");

  useEffect(() => {
    fetchData();
  }, []);

  

  const fetchData = async () => {
    try {
      const cart = await GetAllCart();
      setCartItems(cart);
      setSelectedCartIds(cart.map((item) => item.id));

      const allAlamat = await GetAllAlamat();
      const alamatPembeli = allAlamat.filter((a) => a.id_pembeli == userId);
      setAvailableAddresses(alamatPembeli.map((a) => a.alamat)); // simpan hanya string alamat

      const buyerPoin = cart.length > 0 ? cart[0].poin || 0 : 0;
      setBuyerPoints(buyerPoin);
    } catch (err) {
      console.error(err);
      alert("Gagal mengambil data");
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
    formData.append(
      "metode_pengiriman",
      deliveryMethod === "pickup" ? "ambil sendiri" : "kurir"
    );
    if (deliveryMethod === "shipped") {
      formData.append("alamat_pengiriman", address);
    }
    formData.append("poin_ditukar", pointsToRedeem);
    formData.append("bukti_pembayaran", proof);
    selectedCartIds.forEach((id, i) =>
      formData.append(`selected_cart_ids[${i}]`, id)
    );

    try {
      const res = await axios.post("/api/transaksi-penjualan", formData, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "multipart/form-data",
        },
      });
      alert("Transaksi berhasil!");
      setShowUpload(false);
      setProof(null);
    } catch (err) {
      alert(err.response?.data?.message || "Gagal melakukan transaksi.");
    } finally {
      setIsSubmitting(false);
    }
  };

  const totalHarga = cartItems.reduce(
    (acc, item) => acc + (item.barang?.harga || 0),
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
              <br />
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
                    <span>Rp{item.barang?.harga_barang}</span>
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
