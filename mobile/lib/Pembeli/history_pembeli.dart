import 'package:flutter/material.dart';

class HistoryPembeli extends StatefulWidget {
  const HistoryPembeli({super.key});

  @override
  State<HistoryPembeli> createState() => _HistoryPembeliState();
}

class _HistoryPembeliState extends State<HistoryPembeli> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("History Pembelian"),
        centerTitle: true,
        backgroundColor: Colors.blue,
      ),
      body: ListView.builder(
        itemCount: 3,
        itemBuilder: (context, index) {
          return Pembelian(
            gambar: "https://picsum.photos/id/$index/200/300",
            nama_barang: "Laptop",
            status: "selesai",
          );
        },
      ),
    );
  }
}

class Pembelian extends StatelessWidget {
  final String? gambar;
  final String? nama_barang;
  final String? status;

  const Pembelian({
    super.key,
    this.gambar,
    this.nama_barang,
    this.status,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(10), // jarak luar
      child: Container(
        padding: const EdgeInsets.all(10), // jarak dalam
        decoration: BoxDecoration(
          border: Border.all(color: Colors.grey),
          borderRadius: BorderRadius.circular(8),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Gambar barang
            Container(
              width: 80,
              height: 80,
              color: Colors.grey[300],
              child: gambar != null
                  ? Image.network(
                      gambar!,
                      fit: BoxFit.cover,
                      errorBuilder: (context, error, stackTrace) {
                        return const Icon(Icons.image_not_supported);
                      },
                    )
                  : const Icon(Icons.image_not_supported),
            ),

            const SizedBox(width: 10), // jarak antara gambar dan teks

            // Teks: nama barang dan status
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  nama_barang ?? 'Nama barang tidak tersedia',
                  style: const TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 2), // jarak antar teks
                Text(
                  status ?? 'Status tidak tersedia',
                  style: const TextStyle(
                    fontSize: 16,
                    color: Colors.grey,
                  ),
                ),
                SizedBox(
                  height: 2,
                ),
                Text(
                  "2 Juni 2025",
                  style: const TextStyle(
                    fontSize: 16,
                  ),
                )
              ],
            )
          ],
        ),
      ),
    );
  }
}
