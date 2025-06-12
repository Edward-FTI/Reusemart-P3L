import 'package:flutter/material.dart';
import 'dart:developer';
import 'package:mobile/data/models/penitip/HistoryPenitipanModel.dart';
import 'package:mobile/data/datasource/penitip/HistoryPenitip_data_source.dart';
import 'package:mobile/core/constants/variables.dart';

class HistoryPenitipanPage extends StatefulWidget {
  const HistoryPenitipanPage({super.key});

  @override
  State<HistoryPenitipanPage> createState() => _HistoryPenitipanPageState();
}

class _HistoryPenitipanPageState extends State<HistoryPenitipanPage> {
  List<HistoryPenitipanModel>? historyList;

  @override
  void initState() {
    super.initState();
    loadHistoryPenitipan();
  }

  loadHistoryPenitipan() async {
    final response = await HistoryPenitipDataSource().getHistoryPenitipan();

    if (response != null) {
      log("History penitipan berhasil dimuat: ${response.toJson()}");
      setState(() {
        historyList = response.data;
      });
    } else {
      log("History == null, tidak bisa dimuat");
    }
  }

  @override
  Widget build(BuildContext context) {
    if (historyList == null) {
      return Scaffold(
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: const [
              SizedBox(
                width: 50,
                height: 50,
                child: CircularProgressIndicator(),
              ),
              Divider(
                color: Colors.transparent,
                height: 24,
              ),
              Text(
                "Memuat data history penitipan...",
                style: TextStyle(fontSize: 15, color: Colors.black54),
              )
            ],
          ),
        ),
      );
    }

    if (historyList!.isEmpty) {
      return Scaffold(
        appBar: AppBar(title: const Text("History Penitipan")),
        body: const Center(
          child: Text("Tidak ada data penitipan."),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text("History Penitipan"),
        centerTitle: true,
        backgroundColor: Colors.blue,
      ),
      body: ListView.builder(
        itemCount: historyList!.length,
        itemBuilder: (context, index) {
          final item = historyList![index];
          return Padding(
            padding:
                const EdgeInsets.symmetric(vertical: 8.0, horizontal: 12.0),
            child: Container(
              padding: const EdgeInsets.all(10),
              color: const Color.fromARGB(255, 238, 237, 237),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Image.network(
                    "${Variables.baseUrl}/${item.gambar}",
                    width: 50,
                    height: 50,
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) =>
                        const Icon(Icons.broken_image, size: 50),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          item.namaBarang ?? 'Nama barang tidak ada',
                          style: const TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          item.deskripsi ?? '',
                          style: const TextStyle(
                            fontSize: 14,
                            color: Colors.black87,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 12),
                  Text(
                    item.statusBarang ?? '',
                    style: const TextStyle(
                      fontSize: 12,
                      color: Colors.grey,
                    ),
                  ),
                  // const SizedBox(width: 12),
                  // Text(
                  //   item.kategori?.namaKategori ?? '',
                  //   style: const TextStyle(
                  //     fontSize: 12,
                  //     color: Colors.grey,
                  //   ),
                  // ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
