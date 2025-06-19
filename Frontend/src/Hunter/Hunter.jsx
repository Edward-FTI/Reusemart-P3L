import React, { useEffect, useState } from "react";
import { toast } from "sonner";
import { GetAllHunter } from "../Api/apiHunter";

const KomisiHunter = () => {
  const [barangList, setBarangList] = useState([]);

  useEffect(() => {
  const fetchData = async () => {
    try {
      const data = await GetAllHunter(); // Ambil data dari API
      const today = new Date(); // Hari ini
      const next30Days = new Date();
      next30Days.setDate(today.getDate() + 30); // 30 hari ke depan

      const filtered = data.filter((item) => {
        const tgl = new Date(item.tgl_penitipan);
        return tgl >= today && tgl <= next30Days;
      });

      setBarangList(filtered);
    } catch (err) {
      toast.error("Gagal ambil data komisi hunter");
      console.error(err);
    }
  };

  fetchData();
}, []);



  const totalKomisi = barangList.reduce((total, barang) => {
    if (barang.status_barang.toLowerCase() === "terjual") {
      return total + barang.harga_barang * 0.2 * 0.1;
    }
    return total;
  }, 0);

  return (
    <div className="p-6 bg-white rounded shadow">
      <h2 className="text-xl font-bold mb-4">Komisi Hunter - Bulan Ini</h2>

      <table className="w-full border">
        <thead>
          <tr className="bg-gray-100 text-left">
            <th className="p-2 border">Nama Barang</th>
            <th className="p-2 border">Tanggal Titip</th>
            <th className="p-2 border">Status Barang</th>
            <th className="p-2 border">Komisi</th>
            <th className="p-2 border">Harga Barang</th>
          </tr>
        </thead>
        <tbody>
          {barangList.length > 0 ? (
            barangList.map((barang, index) => (
              <tr key={index} className="border-b">
                <td className="p-2 border">{barang.nama_barang}</td>
                <td className="p-2 border">
                  {new Date(barang.tgl_penitipan).toLocaleDateString("id-ID")}
                </td>
                <td className="p-2 border">{barang.status_barang}</td>
                <td className="p-2 border">
                  {barang.status_barang.toLowerCase() === "terjual"
                    ? `Rp ${(barang.harga_barang * 0.2 * 0.1).toLocaleString("id-ID")}`
                    : "-"}
                </td>
                <td className="p-2 border">{barang.harga_barang}</td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="4" className="text-center p-4">
                Tidak ada data komisi bulan ini.
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {/* Total Komisi */}
      <div className="mt-4 text-right font-semibold">
        Total Komisi: Rp {totalKomisi.toLocaleString("id-ID")}
      </div>
    </div>
  );
};

export default KomisiHunter;
