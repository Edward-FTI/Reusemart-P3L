import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Table from 'react-bootstrap/Table';
import Card from 'react-bootstrap/Card';
import ListGroup from 'react-bootstrap/ListGroup';
import { GetPembeliInfo } from "../Api/apiPembeli";

function ShowProfileCustomer() {
  const [pembeli, setPembeli] = useState(null);
  const [transaksi, setTransaksi] = useState([]);

  useEffect(() => {
    // Memanggil data pembeli dari API helper
    GetPembeliInfo()
      .then((dataPembeli) => {
        setPembeli(dataPembeli);
        // Setelah mendapatkan pembeli, ambil data transaksi penjualan
        axios
          .get("/transaksi_penjualan")
          .then((resTrans) => {
            const allTransaksi = resTrans.data;
            // Filter transaksi berdasarkan id_pembeli
            const filtered = allTransaksi.filter(
              (t) => t.id_pembeli === dataPembeli.id
            );
            setTransaksi(filtered);
          })
          .catch((err) => console.error(err));
      })
      .catch((err) => console.error(err));
  }, []);

  if (!pembeli) return <div>Loading...</div>;

  return (
    <Container fluid>
      <Row>
        <Col md={8}>
          <h3>Transaksi Penjualan</h3>
          <Table striped bordered hover>
            <thead>
              <tr>
                {Object.keys(transaksi[0] || {}).map((key) => (
                  <th key={key}>{key}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {transaksi.map((trans) => (
                <tr key={trans.id}>
                  {Object.values(trans).map((value, idx) => (
                    <td key={idx}>{value}</td>
                  ))}
                </tr>
              ))}
            </tbody>
          </Table>
        </Col>
        <Col md={4}>
          <h3>Profil Pembeli</h3>
          <Card>
            <Card.Img
              variant="top"
              src="/src/assets/Logo/profil.png"
              alt="Profiles"
            />
            <Card.Body>
              <Card.Title>{pembeli.nama}</Card.Title>
              <Card.Text>{pembeli.deskripsi}</Card.Text>
            </Card.Body>
            <ListGroup variant="flush">
              <ListGroup.Item>
                <strong>Email:</strong> {pembeli.email}
              </ListGroup.Item>
              <ListGroup.Item>
                <strong>Point:</strong> {pembeli.point}
              </ListGroup.Item>
              <ListGroup.Item>
                <strong>Saldo:</strong> {pembeli.saldo}
              </ListGroup.Item>
            </ListGroup>
          </Card>
        </Col>
      </Row>
    </Container>
  );
}

export default ShowProfileCustomer;
