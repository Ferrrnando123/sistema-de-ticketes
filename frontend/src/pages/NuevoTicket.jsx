import { useState } from 'react';
import { apiFetch } from '../services/api';
import { useNavigate } from 'react-router-dom';
import './Forms.css';

const NuevoTicket = () => {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  
  const [formData, setFormData] = useState({
    asunto: '',
    descripcion: '',
    ubicacion: '',
    prioridad: 'baja'
  });

  const handleChange = (e) => {
    setFormData({...formData, [e.target.name]: e.target.value});
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const resp = await apiFetch('guardar_ticket', {
        method: 'POST',
        body: formData
      });
      if (resp.success) {
        navigate('/mis-tickets');
      } else {
        setError(resp.message);
      }
    } catch (err) {
      setError('Error de conexión al guardar el ticket.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="page-container animate-fade">
      <h1 className="page-title">Crear Nuevo Ticket</h1>
      <p className="page-subtitle">Describe tu inconveniente y un administrador lo atenderá a la brevedad.</p>
      
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
          </div>
          
          <div className="form-group">
            <label>Descripción Detallada</label>
            <textarea 
              name="descripcion"
              value={formData.descripcion}
              onChange={handleChange}
              rows="4"
              placeholder="Explica qué sucede, equipos afectados, etc."
              required 
            />
          </div>
          
          <div className="form-row">
            <div className="form-group half">
              <label>Ubicación (Edificio/Aula)</label>
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
              <select 
                name="prioridad"
                value={formData.prioridad}
                onChange={handleChange}
                required
              >
                <option value="baja">🟢 Baja (No urgente)</option>
                <option value="media">🟡 Media (Requiere atención)</option>
                <option value="alta">🔴 Alta (Crítico / Bloqueante)</option>
              </select>
            </div>
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
