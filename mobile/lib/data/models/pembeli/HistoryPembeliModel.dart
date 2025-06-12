class HistoryPembeliModel {
  final int? id;
  final int? idPembeli;
  final int? totalHargaPembelian;
  final String? metodePengiriman;
  final String? alamatPengiriman;
  final int? ongkir;
  final String? buktiPembayaran;
  final String? statusPengiriman;
  final String? statusPembelian;
  final String? verifikasiPembayaran;
  final String? tglTransaksi;
  final List<DetailTransaksiModel>? detailTransaksi;

  HistoryPembeliModel({
    this.id,
    this.idPembeli,
    this.totalHargaPembelian,
    this.metodePengiriman,
    this.alamatPengiriman,
    this.ongkir,
    this.buktiPembayaran,
    this.statusPengiriman,
    this.statusPembelian,
    this.verifikasiPembayaran,
    this.tglTransaksi,
    this.detailTransaksi,
  });

  factory HistoryPembeliModel.fromJson(Map<String, dynamic> json) =>
      HistoryPembeliModel(
        id: json['id'],
        idPembeli: json['id_pembeli'],
        totalHargaPembelian: json['total_harga_pembelian'],
        metodePengiriman: json["metode_pengiriman"],
        alamatPengiriman: json["alamat_pengiriman"],
        ongkir: json["ongkir"],
        buktiPembayaran: json["bukti_pembayaran"],
        statusPengiriman: json["status_pengiriman"],
        statusPembelian: json['status_pembelian'],
        verifikasiPembayaran: json["verifikasi_pembayaran"],
        tglTransaksi: json["tgl_transaksi"]?.toString(),
        detailTransaksi: json["detail_transaksi"] != null
            ? List<DetailTransaksiModel>.from(
                json["detail_transaksi"].map(
                  (x) => DetailTransaksiModel.fromJson(x),
                ),
              )
            : null,
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "id_pembeli": idPembeli,
        "total_harga_pembelian": totalHargaPembelian,
        "metode_pengiriman": metodePengiriman,
        "alamat_pengiriman": alamatPengiriman,
        "ongkir": ongkir,
        "bukti_pembayaran": buktiPembayaran,
        "status_pengiriman": statusPengiriman,
        "status_pembelian": statusPembelian,
        "verifikasi_pembayaran": verifikasiPembayaran,
        "tgl_transaksi": tglTransaksi,
        "detail_transaksi": detailTransaksi != null
            ? List<dynamic>.from(detailTransaksi!.map((x) => x.toJson()))
            : null
      };
}

//  model untuk detail transaksi penjualan
class DetailTransaksiModel {
  final int? id;
  final int? idTransaksiPenjualan;
  final int? idBarang;
  final int? hargaSaatTransaksi;
  final barangModel? jenisBarang;

  DetailTransaksiModel({
    this.id,
    this.idTransaksiPenjualan,
    this.idBarang,
    this.hargaSaatTransaksi,
    this.jenisBarang,
  });

  factory DetailTransaksiModel.fromJson(Map<String, dynamic> json) =>
      DetailTransaksiModel(
        id: json["id"],
        idTransaksiPenjualan: json["id_transaksi_penjualan"],
        idBarang: json["id_barang"],
        hargaSaatTransaksi: json["harga_saat_transaksi"],
        jenisBarang: json["barang"] != null
            ? barangModel.fromJson(json["barang"])
            : null,
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "id_transaksi_penjualan": idTransaksiPenjualan,
        "id_barang": idBarang,
        "harga_saat_transaksi": hargaSaatTransaksi,
      };
}

//  model untuk barang yang dipanggil pada model detail transaksi penjualan
class barangModel {
  final int? id;
  final String? namaBarang;
  final String? deskripsi;
  final int? hargaBarang;
  final int? beratBarang;
  final String? tglPenitipan;
  final String? masaPenitipan;
  final String? statusBarang;
  final String? statusGaransi;
  final String? gambar;
  final String? gambarDua;
  final String? tglPengambilan;

  barangModel({
    this.id,
    this.namaBarang,
    this.deskripsi,
    this.hargaBarang,
    this.beratBarang,
    this.tglPenitipan,
    this.masaPenitipan,
    this.statusBarang,
    this.statusGaransi,
    this.gambar,
    this.gambarDua,
    this.tglPengambilan,
  });

  factory barangModel.fromJson(Map<String, dynamic> json) => barangModel(
        id: json['id'],
        namaBarang: json['nama_barang'],
        deskripsi: json['deskripsi'],
        hargaBarang: json['harga_barang'],
        beratBarang: json['berat_barang'],
        tglPenitipan: json['tgl_penitipan'],
        masaPenitipan: json['masa_penitipan'],
        statusBarang: json['status_barang'],
        statusGaransi: json['status_garansi'],
        gambar: json['gambar'],
        gambarDua: json['gambar_dua'],
        tglPengambilan: json['tgl_pengambilan'],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "nama_barang": namaBarang,
        "deskripsi": deskripsi,
        "harga_barang": hargaBarang,
        "berat_barang": beratBarang,
        "tgl_penitipan": tglPenitipan,
        "masa_penitipan": masaPenitipan,
        "status_barang": statusBarang,
        "status_garansi": statusGaransi,
        "gambar": gambar,
        "gambar_dua": gambarDua,
        "tgl_pengambilan": tglPengambilan,
      };
}

class HistoryPembeliResponseModel {
  final List<HistoryPembeliModel>? data;

  HistoryPembeliResponseModel({this.data});

  factory HistoryPembeliResponseModel.fromJson(Map<String, dynamic> json) =>
      HistoryPembeliResponseModel(
        data: json["data"] == null
            ? []
            : List<HistoryPembeliModel>.from(
                json["data"]!.map(
                  (x) => HistoryPembeliModel.fromJson(x),
                ),
              ),
      );

  Map<String, dynamic> toJson() => {
        "data":
            data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson()))
      };
}
