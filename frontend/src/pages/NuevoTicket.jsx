import { useEffect, useMemo, useState } from 'react';
import { apiFetch } from '../services/api';
import { useNavigate } from 'react-router-dom';
import { BlurFade } from '../components/magicui/blur-fade';
import './Forms.css';

const NuevoTicket = () => {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [faqLoading, setFaqLoading] = useState(false);
  const [faq, setFaq] = useState([]);
  const [faqOpenId, setFaqOpenId] = useState(null);

  const [formData, setFormData] = useState({
    asunto: '',
    descripcion: '',
    ubicacion: '',
    prioridad: '',
  });
  const [foto, setFoto] = useState(null);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleFileChange = (e) => {
    if (e.target.files && e.target.files[0]) {
      setFoto(e.target.files[0]);
    }
  };

  const asuntoQuery = useMemo(() => (formData.asunto || '').trim(), [formData.asunto]);

  useEffect(() => {
    let ignore = false;
    if (asuntoQuery.length < 3) {
      setFaq([]);
      setFaqOpenId(null);
      return;
    }

    const t = setTimeout(async () => {
      setFaqLoading(true);
      try {
        const resp = await apiFetch(`buscar_faq&q=${encodeURIComponent(asuntoQuery)}`);
        if (!ignore) {
          setFaq(resp?.success ? resp.data || [] : []);
        }
      } catch {
        if (!ignore) setFaq([]);
      } finally {
        if (!ignore) setFaqLoading(false);
      }
    }, 280);

    return () => {
      ignore = true;
      clearTimeout(t);
    };
  }, [asuntoQuery]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      if (!formData.prioridad) {
        setError('Debes seleccionar una prioridad antes de crear el ticket.');
        setLoading(false);
        return;
      }

      const data = new FormData();
      data.append('asunto', formData.asunto);
      data.append('descripcion', formData.descripcion);
      data.append('ubicacion', formData.ubicacion);
      data.append('prioridad', formData.prioridad);
      if (foto) data.append('foto', foto);

      const resp = await apiFetch('guardar_ticket', { method: 'POST', body: data });
      if (resp.success) {
        navigate('/mis-tickets');
      } else {
        setError(resp.message || 'No se pudo crear el ticket.');
      }
    } catch {
      setError('Error de conexion al guardar el ticket.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="page-container animate-fade">
      <h1 className="page-title">Crear Nuevo Ticket</h1>
      <p className="page-subtitle">Describe tu inconveniente y un administrador lo atendera a la brevedad.</p>

      <div className="card form-card">
        {error && <div className="alert-error">{error}</div>}

        <form onSubmit={handleSubmit} className="custom-form">
          <div className="form-group">
            <label>Asunto / Problema</label>
            <input
              name="asunto"
              value={formData.asunto}
              onChange={handleChange}
              placeholder="Ej. Problema de acceso a WiFi"
              required
            />

            <div className="mt-3">
              <div className="flex items-center justify-between">
                <p className="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sugerencias automaticas</p>
                {faqLoading && <span className="text-xs text-gray-400">Buscando...</span>}
              </div>

              {asuntoQuery.length >= 3 && faq.length === 0 && !faqLoading && (
                <p className="text-xs text-gray-400 mt-2 italic">
                  No hay coincidencias. Si es un incidente real, continua con el ticket.
                </p>
              )}

              {faq.length > 0 && (
                <BlurFade delay={0.08} yOffset={8}>
                  <div className="mt-2 grid gap-2">
                    {faq.map((item) => {
                      const open = faqOpenId === item.id;
                      return (
                        <div
                          key={item.id}
                          className="rounded-xl border border-white/10 bg-white/40 dark:bg-zinc-900/40 backdrop-blur-md p-3 shadow-sm hover:bg-white/60 dark:hover:bg-zinc-900/60 transition"
                        >
                          <div className="flex items-start justify-between gap-3">
                            <div>
                              <p className="font-bold text-[#003366] dark:text-[#ffcc00] leading-tight">{item.titulo}</p>
                              <p className="text-xs text-gray-500 mt-1">Antes de crear el ticket, revisa si esto lo resuelve.</p>
                            </div>
                            <button
                              type="button"
                              className="btn btn-outline"
                              style={{ padding: '0.25rem 0.5rem', fontSize: '0.8rem', whiteSpace: 'nowrap' }}
                              onClick={() => setFaqOpenId(open ? null : item.id)}
                            >
                              {open ? 'Ocultar' : 'Ver'}
                            </button>
                          </div>

                          {open && (
                            <BlurFade delay={0.05} yOffset={6}>
                              <div className="mt-3 text-sm text-gray-600 dark:text-gray-200 leading-relaxed">
                                <div className="rounded-lg bg-white/60 dark:bg-zinc-900/60 p-3 border border-white/10">
                                  {item.solucion}
                                </div>
                                {item.url && (
                                  <a
                                    href={item.url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-block mt-2 text-xs font-bold text-[#003366] dark:text-[#ffcc00] underline underline-offset-4"
                                  >
                                    Abrir guia
                                  </a>
                                )}
                              </div>
                            </BlurFade>
                          )}
                        </div>
                      );
                    })}
                  </div>
                </BlurFade>
              )}
            </div>
          </div>

          <div className="form-group">
            <label>Descripcion Detallada</label>
            <textarea
              name="descripcion"
              value={formData.descripcion}
              onChange={handleChange}
              rows="4"
              placeholder="Explica que sucede, equipos afectados, etc."
              required
            />
          </div>

          <div className="form-row">
            <div className="form-group half">
              <label>Ubicacion (Edificio/Aula)</label>
              <input
                name="ubicacion"
                value={formData.ubicacion}
                onChange={handleChange}
                placeholder="Ej. Edificio B - Aula 12"
                required
              />
            </div>
            <div className="form-group half">
              <label>Nivel de Prioridad</label>
              <select name="prioridad" value={formData.prioridad} onChange={handleChange} required>
                <option value="">Seleccionar prioridad</option>
                <option value="baja">Baja (No urgente)</option>
                <option value="media">Media (Requiere atencion)</option>
                <option value="alta">Alta (Critico / Bloqueante)</option>
                <option value="critica">Critica (Impacto severo)</option>
              </select>
              <p className="text-[11px] text-gray-400 mt-2">Debes seleccionar la prioridad antes de enviar.</p>
            </div>
          </div>

          <div className="form-group">
            <label>Subir Evidencia (Opcional)</label>
            <input
              type="file"
              accept="image/*"
              onChange={handleFileChange}
              className="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#003366] file:text-white hover:file:bg-[#002244] dark:file:bg-[#ffcc00] dark:file:text-[#003366]"
            />
          </div>

          <div className="form-actions">
            <button type="button" className="btn btn-outline" onClick={() => navigate(-1)}>
              Cancelar
            </button>
            <button type="submit" className="btn btn-primary" disabled={loading}>
              {loading ? 'Enviando...' : 'Crear Ticket'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default NuevoTicket;

