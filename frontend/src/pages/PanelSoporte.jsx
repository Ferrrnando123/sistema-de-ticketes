import { useState, useEffect, useMemo } from 'react';
import { apiFetch } from '../services/api';
import { BlurFade } from '../components/magicui/blur-fade';
import { Link } from 'react-router-dom';
import {
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
  Tooltip,
  Legend,
  BarChart,
  Bar,
  CartesianGrid,
  XAxis,
  YAxis,
} from 'recharts';
import './PanelSoporte.css';

const COLORS = ['#003366', '#ffcc00', '#22c55e', '#ef4444', '#8b5cf6', '#06b6d4'];

const PanelSoporte = () => {
  const [data, setData] = useState({ stats: {}, tickets: [], charts: { estado: [], categorias: [] } });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [updatingId, setUpdatingId] = useState(null);
  const [search, setSearch] = useState('');

  useEffect(() => {
    fetchPanel();
  }, []);

  const fetchPanel = async () => {
    try {
      const resp = await apiFetch('panel-soporte');
      if (resp.success) {
        setData(resp.data);
      } else {
        setError(resp.message);
      }
    } catch {
      setError('Error cargando el panel de soporte.');
    } finally {
      setLoading(false);
    }
  };

  const updateStatus = async (id, newStatus) => {
    setUpdatingId(id);
    try {
      const resp = await apiFetch('actualizar_estado', {
        method: 'PATCH',
        body: { id, estado: newStatus },
      });
      if (resp.success) {
        fetchPanel();
      } else {
        alert(resp.message || 'Error actualizando el estado.');
      }
    } catch {
      alert('Error de red al actualizar el estado.');
    } finally {
      setUpdatingId(null);
    }
  };

  const estadoData = useMemo(() => data?.charts?.estado || [], [data]);
  const catData = useMemo(() => data?.charts?.categorias || [], [data]);
  const filteredTickets = useMemo(() => {
    const q = (search || '').trim().toLowerCase();
    if (!q) return data.tickets || [];

    return (data.tickets || []).filter((t) => {
      const idMatch = String(t.id || '').includes(q);
      const emailMatch = String(t.email || '').toLowerCase().includes(q);
      const asuntoMatch = String(t.asunto || '').toLowerCase().includes(q);
      return idMatch || emailMatch || asuntoMatch;
    });
  }, [data.tickets, search]);

  return (
    <div className="page-container admin-panel animate-fade">
      <div className="flex-between">
        <div>
          <h1 className="page-title">Panel de Control IT</h1>
          <p className="page-subtitle">Gestión global de tickets e incidentes.</p>
        </div>
        <button onClick={fetchPanel} className="btn btn-outline" disabled={loading}>
          {loading ? 'Sincronizando...' : 'Refrescar'}
        </button>
      </div>

      {error && <div className="alert-error">{error}</div>}

      {loading && data.tickets.length === 0 ? (
        <>
          <div className="stats-skeleton">
            <div className="stat-skeleton-card"></div>
            <div className="stat-skeleton-card"></div>
            <div className="stat-skeleton-card"></div>
          </div>
          <div className="card admin-table-container">
            <div className="loader-container">
              <div className="spinner"></div>
              <p>Cargando base de datos...</p>
            </div>
          </div>
        </>
      ) : (
        <>
          <div className="stats-grid">
            <div className="stat-card pending">
              <div className="stat-title">Pendientes</div>
              <div className="stat-value">{data.stats?.pendientes || 0}</div>
            </div>
            <div className="stat-card critical">
              <div className="stat-title">Críticos Activos</div>
              <div className="stat-value">{data.stats?.criticos || 0}</div>
            </div>
            <div className="stat-card resolved">
              <div className="stat-title">Resueltos Hoy</div>
              <div className="stat-value">{data.stats?.resueltos_hoy || 0}</div>
            </div>
          </div>

          <div className="card admin-table-container" style={{ marginTop: '1.25rem' }}>
            <BlurFade delay={0.06} yOffset={10}>
              <h3 style={{ marginBottom: '0.75rem' }}>Estadísticas Globales</h3>
            </BlurFade>

            <div className="charts-grid">
              <div className="card" style={{ padding: '1rem' }}>
                <div className="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Tickets por estado</div>
                <div style={{ width: '100%', height: 280 }}>
                  <ResponsiveContainer>
                    <PieChart>
                      <Pie data={estadoData} dataKey="value" nameKey="name" innerRadius={55} outerRadius={85} paddingAngle={3}>
                        {estadoData.map((_, index) => (
                          <Cell key={index} fill={COLORS[index % COLORS.length]} />
                        ))}
                      </Pie>
                      <Tooltip />
                      <Legend />
                    </PieChart>
                  </ResponsiveContainer>
                </div>
              </div>

              <div className="card" style={{ padding: '1rem' }}>
                <div className="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Fallas más comunes (categorías)</div>
                <div style={{ width: '100%', height: 280 }}>
                  <ResponsiveContainer>
                    <BarChart data={catData}>
                      <CartesianGrid strokeDasharray="3 3" opacity={0.25} />
                      <XAxis dataKey="categoria" tick={{ fontSize: 10 }} interval={0} angle={-15} height={50} />
                      <YAxis allowDecimals={false} />
                      <Tooltip />
                      <Bar dataKey="count" fill="#003366" radius={[8, 8, 0, 0]} />
                    </BarChart>
                  </ResponsiveContainer>
                </div>
              </div>
            </div>
          </div>

          <div className="card admin-table-container">
            <div className="table-topbar">
              <h3>Últimos 50 Tickets</h3>
              <input
                className="tickets-filter-input"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                placeholder="Filtrar por usuario (email), asunto o #ID"
              />
            </div>
            <div className="table-responsive">
              <table className="admin-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Asunto</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {filteredTickets.map((t) => (
                    <tr key={t.id}>
                      <td>#{t.id}</td>
                      <td>{new Date(t.created_at).toLocaleDateString()}</td>
                      <td>{t.email}</td>
                      <td>
                        <div className="flex items-center gap-2">
                          <strong>{t.asunto}</strong>
                        </div>
                      </td>
                      <td>
                        <span className={`prio-badge prio-${t.prioridad}`}>{t.prioridad}</span>
                      </td>
                      <td>
                        <select
                          className={`status-select status-${t.estado}`}
                          value={t.estado}
                          onChange={(e) => updateStatus(t.id, e.target.value)}
                          disabled={updatingId === t.id}
                        >
                          <option value="abierto">Abierto</option>
                          <option value="en_proceso">En Proceso</option>
                          <option value="resuelto">Resuelto</option>
                        </select>
                      </td>
                      <td style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
                        <Link to={`/tickets/${t.id}`} className="btn btn-outline" style={{ padding: '0.25rem 0.5rem', fontSize: '0.8rem', textDecoration: 'none' }}>
                          Detalle/Chat
                        </Link>
                        {t.foto_url ? (
                          <a
                            href={t.foto_url}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="btn btn-outline"
                            style={{ padding: '0.25rem 0.5rem', fontSize: '0.8rem', textDecoration: 'none', display: 'inline-block' }}
                          >
                            Ver Imagen
                          </a>
                        ) : (
                          <span className="text-gray-400 text-xs italic">Sin imagen</span>
                        )}
                      </td>
                    </tr>
                  ))}
                  {filteredTickets.length === 0 && (
                    <tr>
                      <td colSpan="7" style={{ textAlign: 'center', color: 'var(--secondary-color)' }}>
                        No hay tickets que coincidan con el filtro.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </>
      )}
    </div>
  );
};

export default PanelSoporte;
