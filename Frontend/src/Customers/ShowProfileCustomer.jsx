import React from "react";
import {
  GetAllPenitip,
  GetPenitipByNama,
  GetPenitipById,
} from "../Api/apiPenitip";

import { Row, Col, Button, Card } from 'react-bootstrap';

function ShowProfileCustomer() {
  return (
    <main className="container mt-4">
      <Row>
        {/* Left Side: Articles with Data Table */}
        <Col md={8}>
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h2>History Transaksi</h2>
          </div>

          <table className="table table-bordered table-hover">
            <thead className="table-light">
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No KTP</th>
                <th>Gambar KTP</th>
                <th>Saldo</th>
                <th>Point</th>
                <th>Email</th>
                <th>Badge</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              {/* Map your PenitipList here */}
              {/* Example data */}
              <tr>
                <td>1</td>
                <td>Adji Ma'aarij</td>
                <td>1234567890</td>
                <td><img src="assets/ktp_sample.jpg" alt="Gambar KTP" style={{ width: '50px', height: '50px' }} /></td>
                <td>Rp 500,000</td>
                <td>100</td>
                <td>adji@mail.com</td>
                <td>Gold</td>
                <td>
                  <button className="btn btn-sm btn-warning me-2">Edit</button>
                  <button className="btn btn-sm btn-danger">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </Col>

        {/* Right Side: Profile */}
        <Col md={4}>
          <aside>
            <article className="card profile" id="About">
              <figure>
                <h2>Profile</h2>
                <img src="assets/Pribadi.JPG" alt="Saya" />
              </figure>
              <ul>
                <li><h4>Nama: Adji Ma'aarij</h4></li>
                <li>
                  <p>
                    Tentang Saya: Saya memiliki banyak sekali hobi seperti bermain
                    game, membaca novel, menonton film, coding, dan baru-baru ini
                    saya menyukai traveling. Setelah saya kuliah saya mulai menyukai
                    traveling. Saya suka menjelajahi tempat baru yang belum pernah
                    saya kunjungi. Kali ini, saya akan berbagi beberapa tempat yang
                    telah saya kunjungi di daerah dekat tempat tinggal saya di
                    project web ini.
                  </p>
                </li>
              </ul>
            </article>
          </aside>
        </Col>
      </Row>
    </main>
  );
}

export default ShowProfileCustomer;
