class PenitipModel {
  final int? id;
  final String? nama_penitip;
  final String? no_ktp;
  final String? alamat;
  final double? saldo;
  final int? point;
  final String? email;
  final String? badge;

  PenitipModel({
    this.id,
    this.nama_penitip,
    this.no_ktp,
    this.alamat,
    this.saldo,
    this.point,
    this.email,
    this.badge,
  });

  factory PenitipModel.fromJson(Map<String, dynamic> json) => PenitipModel(
        id: json['id'],
        nama_penitip: json['nama_penitip'],
        no_ktp: json['no_ktp'],
        alamat: json['alamat'],
        saldo: (json['saldo'] is int)
            ? (json['saldo'] as int).toDouble()
            : json['saldo'] as double?,
        point: json['point'],
        email: json['email'],
        badge: json['badge'],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "nama_penitip": nama_penitip,
        "no_ktp": no_ktp,
        "alamat": alamat,
        "saldo": saldo,
        "point": point,
        "email": email,
        "badge": badge,
      };
}

class PenitipResponeModel {
  final List<PenitipModel>? data;

  PenitipResponeModel({this.data});

  factory PenitipResponeModel.fromJson(Map<String, dynamic> json) =>
      PenitipResponeModel(
        data: json["data"] == null
            ? []
            : List<PenitipModel>.from(
                json["data"]!.map((x) => PenitipModel.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "data":
            data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson()))
      };
}