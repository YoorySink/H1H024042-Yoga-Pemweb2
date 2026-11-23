import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { simpanMahasiswa } from '../api/layananMahasiswa'
import { backend, normal } from '../api/konfigurasiBackend'

const daftarKolom = [
  { nama: 'program_studi_id', label: 'ID Program Studi', tipe: 'number' },
  { nama: 'nim', label: 'NIM', tipe: 'text' },
  { nama: 'nama', label: 'Nama', tipe: 'text' },
  { nama: 'email', label: 'Email', tipe: 'text' },
  { nama: 'angkatan', label: 'Angkatan', tipe: 'number' },
  { nama: 'ipk', label: 'IPK', tipe: 'number' },
]

export default function FormMahasiswa() {
  const [isian, setIsian] = useState({
    program_studi_id: 1,
    nim: '',
    nama: '',
    email: '',
    angkatan: 2024,
    ipk: 0,
  })
  const [galat, setGalat] = useState({})
  const [pesanGalat, setPesanGalat] = useState('')
  const [sedangMemuat, setSedangMemuat] = useState(false)
  const navigasi = useNavigate()

  function ubah(peristiwa) {
    setIsian({ ...isian, [peristiwa.target.name]: peristiwa.target.value })
  }

  async function tanganiKirim(peristiwa) {
    peristiwa.preventDefault()
    setGalat({})
    setPesanGalat('')
    setSedangMemuat(true)

    try {
      await simpanMahasiswa({
        ...isian,
        program_studi_id: Number(isian.program_studi_id),
        angkatan: Number(isian.angkatan),
        ipk: Number(isian.ipk),
      })
      navigasi('/mahasiswa')
    } catch (e) {
      if (e.response && e.response.status === 422) {
        setGalat(backend.bacaGalat(e.response.data))
        setPesanGalat(
          e.response.data.pesan || e.response.data.message || 'Data tidak valid'
        )
      } else if (e.response && e.response.status === 403) {
        setPesanGalat('Akun Anda tidak berwenang menambah data')
      } else if (e.response) {
        setPesanGalat('Gagal menyimpan data')
      } else {
        setPesanGalat('Tidak dapat menghubungi server')
      }
    } finally {
      setSedangMemuat(false)
    }
  }

  return (
    <div style={{ maxWidth: 420, margin: '40px auto' }}>
      <h1>Tambah Mahasiswa</h1>
      {pesanGalat && <p style={{ color: 'crimson' }}>{pesanGalat}</p>}
      <form onSubmit={tanganiKirim}>
        {daftarKolom.map((kolom) => (
          <div key={kolom.nama} style={{ marginBottom: 12 }}>
            <label htmlFor={kolom.nama}>{kolom.label}</label>
            <input
              id={kolom.nama}
              name={kolom.nama}
              type={kolom.tipe}
              step={kolom.nama === 'ipk' ? '0.01' : undefined}
              value={isian[kolom.nama]}
              onChange={ubah}
            />
            {galat[normal(kolom.nama)] && (
              <div style={{ color: 'crimson', fontSize: 14 }}>
                {galat[normal(kolom.nama)]}
              </div>
            )}
          </div>
        ))}
        <button type="submit" disabled={sedangMemuat}>
          {sedangMemuat ? 'Menyimpan' : 'Simpan'}
        </button>{' '}
        <Link to="/mahasiswa">Batal</Link>
      </form>
    </div>
  )
}