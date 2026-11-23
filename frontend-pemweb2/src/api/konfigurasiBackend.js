export function normal(nama) {
  return String(nama).replace(/_/g, '').toLowerCase()
}

const profil = {
  laravel: {
    baseURL: import.meta.env.VITE_API_LARAVEL,
    medanSandi: 'password',
    parameterHalaman: 'page',
    bacaMeta: (meta) => ({
      halaman: meta.current_page,
      totalHalaman: meta.last_page,
    }),
    bacaGalat: (data) => {
      const hasil = {}
      const errors = data.errors || {}
      for (const kunci in errors) {
        hasil[normal(kunci)] = errors[kunci][0]
      }
      return hasil
    },
  },
  fiber: {
    baseURL: import.meta.env.VITE_API_FIBER,
    medanSandi: 'kata_sandi',
    parameterHalaman: 'halaman',
    bacaMeta: (meta) => ({
      halaman: meta.halaman,
      totalHalaman: meta.total_halaman,
    }),
    bacaGalat: (data) => {
      const hasil = {}
      const daftar = data.galat || []
      daftar.forEach((item) => {
        hasil[normal(item.field)] = item.pesan
      })
      return hasil
    },
  },
}

export const namaBackend =
  import.meta.env.VITE_BACKEND === 'fiber' ? 'fiber' : 'laravel'

export const backend = profil[namaBackend]