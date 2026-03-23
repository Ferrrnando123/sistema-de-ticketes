import { useState, useEffect } from 'react';
import { apiFetch } from '../services/api';
import './PanelSoporte.css';

const PanelSoporte = () => {
  const [data, setData] = useState({ stats: {}, tickets: [] });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [updatingId, setUpdatingId] = useState(null);

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
    } catch (err) {
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
        body: { id, estado: newStatus }
      });
      if (resp.success) {
        // Optimistic UI update or refetch
        fetchPanel();
      } else {
        alert(resp.message || 'Error actualizando el estado.');
      }
    } catch (err) {
      alert('Error de red al actualizar el estado.');
    } finally {
      setUpdatingId(null);
    }
  };

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

      <div className="card admin-table-container">
        <h3 style={{marginBottom: '1rem'}}>Últimos 50 Tickets</h3>
        {loading && data.tickets.length === 0 ? (
          <div className="loader-container">Cargando base de datos...</div>
        ) : (
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
                {data.tickets.map(t => (
                  <tr key={t.id}>
                    <td>#{t.id}</td>
                    <td>{new Date(t.created_at).toLocaleDateString()}</td>
                    <td>{t.email}</td>
                    <td>
                      <div className="flex items-center gap-2">
                        <strong>{t.asunto}</strong>
                        {t.foto_url && (
                          <a 
                            href={t.foto_url} 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            title="Ver Evidencia"
                            className="text-blue-500 hover:text-blue-700"
                          >
                            🖼️
                          </a>
                        )}
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
                    <td>
                      {/* Aquí podrían ir más acciones como Ver Detalles, Asignar a... */}
                      <button className="btn btn-outline" style={{padding: '0.25rem 0.5rem', fontSize: '0.8rem'}}>
                        Detalles
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
};

export default PanelSoporte;
