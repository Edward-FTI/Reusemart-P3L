import 'package:flutter/material.dart';
import 'package:mobile/core/constants/variables.dart';
import 'dart:developer';
import 'package:mobile/data/models/pembeli/HistoryPembeliModel.dart';
import 'package:mobile/data/datasource/pembeli/historyPembeli_data_source.dart';
import 'package:intl/intl.dart';

class HistoryPembeli extends StatefulWidget {
  const HistoryPembeli({super.key});

  @override
  State<HistoryPembeli> createState() => _HistoryPembeliState();
}

class _HistoryPembeliState extends State<HistoryPembeli> {
  List<HistoryPembeliModel>? historyList;
  List<HistoryPembeliModel>? filteredList;

  DateTime? startDate;
  DateTime? endDate;

  @override
  void initState() {
    super.initState();
    loadHistoryPembelian();
  }

  loadHistoryPembelian() async {
    final response = await HistoryPembeliDataSource().getHistoryPembeli();

    if (response != null) {
      log("History pembelian berhasil dimuat: ${response.toJson()}");
      setState(() {
        historyList = response.data;
        filteredList = response.data;
      });
    } else {
      log("History == null, tidak bisa dimuat");
    }
  }

  void filterByDate() {
    if (historyList == null) return;

    if (startDate == null || endDate == null) {
      // Tidak ada tanggal dipilih, tampilkan semua
      setState(() {
        filteredList = historyList;
      });
    } else {
      // Filter berdasarkan rentang tanggal
      setState(() {
        filteredList = historyList!.where((item) {
          if (item.tglTransaksi == null) return false;
          final transaksiDate = DateTime.parse(item.tglTransaksi!);
          return transaksiDate
                  .isAfter(startDate!.subtract(const Duration(days: 1))) &&
              transaksiDate.isBefore(endDate!.add(const Duration(days: 1)));
        }).toList();
      });
    }
  }

  Future<void> selectDate(BuildContext context, bool isStart) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2023),
      lastDate: DateTime(2100),
    );
    if (picked != null) {
      setState(() {
        if (isStart) {
          startDate = picked;
        } else {
          endDate = picked;
        }
      });
    }
  }

  String formatTanggal(String? tanggalString) {
    if (tanggalString == null) return "-";
    try {
      DateTime dateTime = DateTime.parse(tanggalString);
      return DateFormat("dd-MM-yyyy").format(dateTime);
    } catch (e) {
      return "-";
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("History Pembelian"),
        centerTitle: true,
        backgroundColor: Colors.blue,
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                Expanded(
                  child: InkWell(
                    onTap: () => selectDate(context, true),
                    child: Container(
                      padding: const EdgeInsets.symmetric(
                          vertical: 12, horizontal: 10),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        startDate != null
                            ? DateFormat("dd-MM-yyyy").format(startDate!)
                            : "Tanggal Awal",
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: InkWell(
                    onTap: () => selectDate(context, false),
                    child: Container(
                      padding: const EdgeInsets.symmetric(
                          vertical: 12, horizontal: 10),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        endDate != null
                            ? DateFormat("dd-MM-yyyy").format(endDate!)
                            : "Tanggal Akhir",
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.filter_alt),
                  tooltip: "Terapkan Filter",
                  onPressed: () {
                    if (startDate != null && endDate != null) {
                      filterByDate();
                    }
                  },
                ),
                IconButton(
                  icon: const Icon(Icons.refresh),
                  tooltip: "Reset Filter",
                  onPressed: () {
                    setState(() {
                      startDate = null;
                      endDate = null;
                      filteredList = historyList;
                    });
                  },
                ),
              ],
            ),
          ),
          Expanded(
            child: filteredList == null
                ? const Center(child: CircularProgressIndicator())
                : filteredList!.isEmpty
                    ? const Center(child: Text("Tidak ada data pembelian."))
                    : ListView.builder(
                        itemCount: filteredList!.length,
                        padding: const EdgeInsets.all(12),
                        itemBuilder: (context, index) {
                          final item = filteredList![index];
                          return Card(
                            margin: const EdgeInsets.symmetric(vertical: 8),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            elevation: 2,
                            child: Padding(
                              padding: const EdgeInsets.all(16),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    "Tanggal: ${formatTanggal(item.tglTransaksi)}",
                                    style: const TextStyle(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.black87,
                                    ),
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    "Total Harga: Rp${item.totalHargaPembelian}",
                                    style: const TextStyle(
                                      fontSize: 14,
                                      color: Colors.black87,
                                    ),
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    "Metode Pengiriman: ${item.metodePengiriman}",
                                    style: const TextStyle(
                                      fontSize: 14,
                                      color: Colors.black54,
                                    ),
                                  ),
                                  const SizedBox(height: 12),
                                  Align(
                                    alignment: Alignment.centerRight,
                                    child: ElevatedButton(
                                      onPressed: () {
                                        showModalBottomSheet(
                                          context: context,
                                          isScrollControlled: true,
                                          builder: (context) => SizedBox(
                                            height: MediaQuery.of(context)
                                                    .size
                                                    .height *
                                                0.6,
                                            child: DetailTransaksiWidget(
                                              detailTransaksi:
                                                  item.detailTransaksi ?? [],
                                            ),
                                          ),
                                        );
                                      },
                                      child: const Text("Detail"),
                                    ),
                                  )
                                ],
                              ),
                            ),
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }
}

class DetailTransaksiWidget extends StatelessWidget {
  final List<DetailTransaksiModel> detailTransaksi;

  const DetailTransaksiWidget({super.key, required this.detailTransaksi});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      itemCount: detailTransaksi.length,
      itemBuilder: (context, index) {
        final detail = detailTransaksi[index];
        final barang = detail.jenisBarang;

        return Card(
          margin: const EdgeInsets.symmetric(vertical: 8, horizontal: 12),
          child: ListTile(
            leading: barang?.gambar != null
                ? Image.network(
                    "${Variables.baseUrl}/${barang!.gambar}",
                    width: 50,
                    height: 50,
                    fit: BoxFit.cover,
                  )
                : const Icon(Icons.image),
            title: Text(barang?.namaBarang ?? "Tidak ada nama"),
            subtitle: Text(
              "Harga: Rp ${detail.hargaSaatTransaksi?.toString() ?? '0'}",
            ),
          ),
        );
      },
    );
  }
}
