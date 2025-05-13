// ShowProfilePenitip.jsx
import React, { useEffect, useState } from "react";
import { GetPenitipById } from "../Api/apiPenitip";
import { Row, Col } from "react-bootstrap";

function ShowProfilePenitip() {
  const [penitip, setPenitip] = useState(null);

  const fetchPenitipByLogin = async () => {
    try {
      const userId = sessionStorage.getItem("user_id");
      if (!userId) {
        alert("User belum login");
        window.location.href = "/login"; // redirect ke login
        return;
      }

      const data = await GetPenitipById(userId);
      setPenitip(data);
    } catch (error) {
      alert("Gagal mengambil data profile penitip");
      console.error(error);
    }
  };

  useEffect(() => {
    fetchPenitipByLogin();
  }, []);

  return (
    <main className="container mt-4">
      <Row className="justify-content-center">
        <Col md={6}>
          <article className="card profile" id="About">
            <h2 className="card-header">Profile Penitip</h2>
            <div className="card-body">
              {penitip ? (
                <ul className="list-unstyled">
                  <li>
                    <strong>Nama:</strong> {penitip.nama}
                  </li>
                  <li>
                    <strong>No KTP:</strong> {penitip.no_ktp}
                  </li>
                  <li>
                    <strong>Saldo:</strong> Rp{" "}
                    {parseInt(penitip.saldo).toLocaleString("id-ID")}
                  </li>
                  <li>
                    <strong>Point:</strong> {penitip.point}
                  </li>
                  <li>
                    <strong>Email:</strong> {penitip.email}
                  </li>
                  <li>
                    <strong>Badge:</strong> {penitip.badge}
                  </li>
                </ul>
              ) : (
                <p>Memuat data profile penitip...</p>
              )}
            </div>
          </article>
        </Col>
      </Row>
    </main>
  );
}

export default ShowProfilePenitip;
