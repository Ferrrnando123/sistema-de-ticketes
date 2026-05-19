const blockedTerms = [
  'idiota', 'imbecil', 'estupido', 'pendejo', 'pendeja', 'mierda', 'puta', 'puto',
  'malparido', 'malparida', 'cerote', 'hijueputa', 'hijo de puta', 'hija de puta',
  'cabron', 'cabrona', 'verga', 'culo', 'culero', 'culera', 'maricon', 'marica',
  'zorra', 'prostituta', 'perra', 'baboso', 'babosa', 'tarado', 'tarada',
  'inutil', 'inservible', 'asqueroso', 'asquerosa', 'comemierda', 'fuck', 'shit',
  'pornografia', 'pornografico', 'pornografica', 'sexo explicito', 'nudes', 'pornhub',
  'bestialidad', 'zoofilia', 'child porn', 'cp',
  'bomba', 'explosivo', 'detonar', 'masacre', 'asesinar', 'te voy a matar',
  'tiroteo', 'ataque armado', 'isis', 'daesh', 'al qaeda', 'taliban', 'terrorista',
  'terrorismo', 'atentado', 'arma quimica', 'arma biologica',
  'suicidarme', 'suicidate', 'quitarme la vida', 'autolesion', 'me quiero matar'
];

const normalize = (text) => {
  if (!text) return '';
  const map = {
    a: /[áàä]/g,
    e: /[éèë]/g,
    i: /[íìï]/g,
    o: /[óòö]/g,
    u: /[úùü]/g,
    n: /ñ/g
  };

  let out = String(text).toLowerCase();
  Object.keys(map).forEach((k) => {
    out = out.replace(map[k], k);
  });
  out = out.replace(/[^a-z0-9\s]/g, ' ');
  out = out.replace(/\s+/g, ' ').trim();
  return out;
};

export const hasInappropriateContent = (text) => {
  const normalized = normalize(text);
  if (!normalized) return false;

  return blockedTerms.some((term) => {
    const normalizedTerm = normalize(term);
    if (!normalizedTerm) return false;
    if (normalizedTerm.includes(' ')) {
      return normalized.includes(normalizedTerm);
    }
    const re = new RegExp(`\\b${normalizedTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}\\b`, 'i');
    return re.test(normalized);
  });
};

