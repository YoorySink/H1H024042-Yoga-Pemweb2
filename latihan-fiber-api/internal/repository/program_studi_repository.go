package repository

import (
	"time"

	"latihan-fiber-api/internal/config"
)

type ProgramStudiDenganJumlah struct {
	ID              uint      `json:"id"`
	Kode            string    `json:"kode"`
	Nama            string    `json:"nama"`
	Jenjang         string    `json:"jenjang"`
	JumlahMahasiswa int64     `json:"jumlah_mahasiswa"`
	CreatedAt       time.Time `json:"dibuat_pada"`
	UpdatedAt       time.Time `json:"diubah_pada"`
}

func AmbilProgramStudi() ([]ProgramStudiDenganJumlah, error) {
	var daftar []ProgramStudiDenganJumlah

	err := config.DB.
		Table("program_studis").
		Select("program_studis.id, program_studis.kode, program_studis.nama, program_studis.jenjang, program_studis.created_at, program_studis.updated_at, COUNT(mahasiswas.id) AS jumlah_mahasiswa").
		Joins("LEFT JOIN mahasiswas ON mahasiswas.program_studi_id = program_studis.id").
		Group("program_studis.id").
		Order("program_studis.nama ASC").
		Scan(&daftar).Error

	return daftar, err
}