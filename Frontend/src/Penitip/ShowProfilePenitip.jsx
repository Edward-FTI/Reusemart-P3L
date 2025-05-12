import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { Card, Container, Row, Col } from "react-bootstrap";
import { GetPenitipById } from "../Api/apiPenitip";

const ShowProfilePenitip = () => {
  const { id } = useParams();
  const [penitip, setPenitip] = useState(null);

  useEffect(() => {
    const fetchPenitip = async () => {
      try {
        const data = await GetPenitipById(id);
        setPenitip(data);
      } catch (error) {
        console.error("Gagal mengambil data penitip:", error);
      }
    };

    fetchPenitip();
  }, [id]);

  if (!penitip)
    return <div className="text-center mt-5">Memuat data penitip...</div>;

  return (
    <Container className="mt-5">
      <Row className="justify-content-center">
        <Col md={8}>
          <Card className="p-4 shadow">
            <Card.Body>
              <h3 className="mb-4 text-center">Profil Penitip</h3>
              <p>
                <strong>Nama Penitip:</strong> {penitip.nama_penitip}
              </p>
              <p>
                <strong>No. KTP:</strong> {penitip.no_ktp}
              </p>
              <p>
                <strong>Saldo:</strong> Rp{" "}
                {Number(penitip.saldo).toLocaleString()}
              </p>
              <p>
                <strong>Point:</strong> {penitip.point}
              </p>
              <p>
                <strong>Email:</strong> {penitip.email}
              </p>
              <p>
                <strong>Badge:</strong> {penitip.badge}
              </p>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </Container>
  );
};

export default ShowProfilePenitip;
