class HistoryPenitipanModel {
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
  final KategoriModel? kategori;

  HistoryPenitipanModel({
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
    this.kategori,
  });

  factory HistoryPenitipanModel.fromJson(Map<String, dynamic> json) =>
      HistoryPenitipanModel(
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
        kategori: json['kategori_barang'] != null
            ? KategoriModel.fromJson(json['kategori_barang'])
            : null,
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
        "kategori_barang": kategori?.toJson(),
      };
}

class KategoriModel {
  final int? id;
  final String? namaKategori;

  KategoriModel({
    this.id,
    this.namaKategori,
  });

  factory KategoriModel.fromJson(Map<String, dynamic> json) => KategoriModel(
        id: json['id'],
        namaKategori: json['nama_kategori'],
      );
  Map<String, dynamic> toJson() => {
        "id": id,
        "nama_kategori": namaKategori,
      };
}

class HistoryPenitipanResponseModel {
  final List<HistoryPenitipanModel>? data;

  HistoryPenitipanResponseModel({this.data});

  factory HistoryPenitipanResponseModel.fromJson(Map<String, dynamic> json) =>
      HistoryPenitipanResponseModel(
        data: json["data"] == null
            ? []
            : List<HistoryPenitipanModel>.from(
                json["data"]!.map(
                  (x) => HistoryPenitipanModel.fromJson(x),
                ),
              ),
      );

  Map<String, dynamic> toJson() => {
        "data":
            data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson()))
      };
}
