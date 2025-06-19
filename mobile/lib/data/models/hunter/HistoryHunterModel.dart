class HistoryHunterResponseModel {
  final List<HistoryHunterModel> data;

  HistoryHunterResponseModel({required this.data});

  factory HistoryHunterResponseModel.fromJson(Map<String, dynamic> json) =>
      HistoryHunterResponseModel(
        data: (json['data'] as List?)
                ?.map((e) => HistoryHunterModel.fromJson(e))
                .toList() ??
            [],
      );

  Map<String, dynamic> toJson() => {
        "data": data.map((x) => x.toJson()).toList(),
      };
}

class HistoryHunterModel {
  final int? id;
  final int? idPembeli;
  final int? totalHarga;
  final String? metodePengiriman;
  final String? alamat;
  final int? ongkir;
  final String? buktiBayar;
  final String? statusPengiriman;
  final String? statusPembelian;
  final String? verifikasi;
  final String? tglTransaksi;
  final List<DetailTransaksiModel> detail;
  final PembeliModel? pembeli;

  HistoryHunterModel({
    this.id,
    this.idPembeli,
    this.totalHarga,
    this.metodePengiriman,
    this.alamat,
    this.ongkir,
    this.buktiBayar,
    this.statusPengiriman,
    this.statusPembelian,
    this.verifikasi,
    this.tglTransaksi,
    this.detail = const [],
    this.pembeli,
  });

  factory HistoryHunterModel.fromJson(Map<String, dynamic> json) =>
      HistoryHunterModel(
        id: json['id'],
        idPembeli: json['id_pembeli'],
        totalHarga: json['total_harga_pembelian'],
        metodePengiriman: json['metode_pengiriman'],
        alamat: json['alamat_pengiriman'],
        ongkir: json['ongkir'],
        buktiBayar: json['bukti_pembayaran'],
        statusPengiriman: json['status_pengiriman'],
        statusPembelian: json['status_pembelian'],
        verifikasi: json['verifikasi_pembayaran'],
        tglTransaksi: json['tgl_transaksi'],
        detail: (json['detail_transaksi'] as List?)
                ?.map((e) => DetailTransaksiModel.fromJson(e))
                .toList() ??
            [],
        pembeli: json['pembeli'] != null
            ? PembeliModel.fromJson(json['pembeli'])
            : null,
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "id_pembeli": idPembeli,
        "total_harga_pembelian": totalHarga,
        "metode_pengiriman": metodePengiriman,
        "alamat_pengiriman": alamat,
        "ongkir": ongkir,
        "bukti_pembayaran": buktiBayar,
        "status_pengiriman": statusPengiriman,
        "status_pembelian": statusPembelian,
        "verifikasi_pembayaran": verifikasi,
        "tgl_transaksi": tglTransaksi,
        "detail_transaksi": detail.map((x) => x.toJson()).toList(),
        "pembeli": pembeli?.toJson(),
      };
}

class DetailTransaksiModel {
  final int? id;
  final int? idTransaksi;
  final int? idBarang;
  final int? hargaSaatTransaksi;
  final BarangModel? barang;

  DetailTransaksiModel({
    this.id,
    this.idTransaksi,
    this.idBarang,
    this.hargaSaatTransaksi,
    this.barang,
  });

  factory DetailTransaksiModel.fromJson(Map<String, dynamic> json) =>
      DetailTransaksiModel(
        id: json['id'],
        idTransaksi: json['id_transaksi_penjualan'],
        idBarang: json['id_barang'],
        hargaSaatTransaksi: json['harga_saat_transaksi'],
        barang: json['barang'] != null
            ? BarangModel.fromJson(json['barang'])
            : null,
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "id_transaksi_penjualan": idTransaksi,
        "id_barang": idBarang,
        "harga_saat_transaksi": hargaSaatTransaksi,
        "barang": barang?.toJson(),
      };
}

class BarangModel {
  final int? id;
  final int? idPenitip;
  final int? idKategori;
  final int? idPegawai;
  final int? idHunter;
  final String? tglPenitipan;
  final String? masaPenitipan;
  final int? penambahanDurasi;
  final String? namaBarang;
  final int? hargaBarang;
  final int? beratBarang;
  final String? deskripsi;
  final String? statusGaransi;
  final String? statusBarang;
  final String? tglPengambilan;
  final String? gambar;
  final String? gambarDua;

  BarangModel({
    this.id,
    this.idPenitip,
    this.idKategori,
    this.idPegawai,
    this.idHunter,
    this.tglPenitipan,
    this.masaPenitipan,
    this.penambahanDurasi,
    this.namaBarang,
    this.hargaBarang,
    this.beratBarang,
    this.deskripsi,
    this.statusGaransi,
    this.statusBarang,
    this.tglPengambilan,
    this.gambar,
    this.gambarDua,
  });

  factory BarangModel.fromJson(Map<String, dynamic> json) => BarangModel(
        id: json['id'],
        idPenitip: json['id_penitip'],
        idKategori: json['id_kategori'],
        idPegawai: json['id_pegawai'],
        idHunter: json['id_hunter'],
        tglPenitipan: json['tgl_penitipan'],
        masaPenitipan: json['masa_penitipan'],
        penambahanDurasi: json['penambahan_durasi'],
        namaBarang: json['nama_barang'],
        hargaBarang: json['harga_barang'],
        beratBarang: json['berat_barang'],
        deskripsi: json['deskripsi'],
        statusGaransi: json['status_garansi'],
        statusBarang: json['status_barang'],
        tglPengambilan: json['tgl_pengambilan'],
        gambar: json['gambar'],
        gambarDua: json['gambar_dua'],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "id_penitip": idPenitip,
        "id_kategori": idKategori,
        "id_pegawai": idPegawai,
        "id_hunter": idHunter,
        "tgl_penitipan": tglPenitipan,
        "masa_penitipan": masaPenitipan,
        "penambahan_durasi": penambahanDurasi,
        "nama_barang": namaBarang,
        "harga_barang": hargaBarang,
        "berat_barang": beratBarang,
        "deskripsi": deskripsi,
        "status_garansi": statusGaransi,
        "status_barang": statusBarang,
        "tgl_pengambilan": tglPengambilan,
        "gambar": gambar,
        "gambar_dua": gambarDua,
      };
}

class PembeliModel {
  final int? id;
  final String? nama;
  final String? email;

  PembeliModel({this.id, this.nama, this.email});

  factory PembeliModel.fromJson(Map<String, dynamic> json) => PembeliModel(
        id: json['id'],
        nama: json['nama_pembeli'],
        email: json['email'],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "nama_pembeli": nama,
        "email": email,
      };
}
