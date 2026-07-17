// All 28 districts of Malawi — the same list the website offers in its
// registration/profile dropdowns. Kept canonical here so the app never shows a
// partial list (it previously derived districts from existing tutors only).
export const MALAWI_DISTRICTS = [
  // Northern Region
  'Chitipa',
  'Karonga',
  'Likoma',
  'Mzimba',
  'Nkhata Bay',
  'Rumphi',
  // Central Region
  'Dedza',
  'Dowa',
  'Kasungu',
  'Lilongwe',
  'Mchinji',
  'Nkhotakota',
  'Ntcheu',
  'Ntchisi',
  'Salima',
  // Southern Region
  'Balaka',
  'Blantyre',
  'Chikwawa',
  'Chiradzulu',
  'Machinga',
  'Mangochi',
  'Mulanje',
  'Mwanza',
  'Neno',
  'Nsanje',
  'Phalombe',
  'Thyolo',
  'Zomba',
];

export const MALAWI_DISTRICTS_SORTED = [...MALAWI_DISTRICTS].sort((a, b) => a.localeCompare(b));
