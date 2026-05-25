import { useEffect, useMemo, useRef, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { apiFetch } from '../services/api';
import { useAuth } from '../context/AuthContext';
import { BlurFade } from '../components/magicui/blur-fade';
import { hasInappropriateContent } from '../lib/contentFilter';
import './TicketDetalle.css';

const TicketDetalle = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { user } = useAuth();
  const ticketId = useMemo(() => Number(id), [id]);

  const [ticket, setTicket] = useState(null);
  const [msgs, setMsgs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [sending, setSending] = useState(false);
  const [text, setText] = useState('');
  const [chatError, setChatError] = useState('');

  const bodyRef = useRef(null);

  const scrollToBottom = () => {
    const el = bodyRef.current;
    if (!el) return;
    el.scrollTop = el.scrollHeight;
  };

  const fetchTicket = async () => {
    const resp = await apiFetch(`ticket_detalle&id=${ticketId}`);
    if (!resp.success) throw new Error(resp.message || 'Error cargando ticket');
    setTicket(resp.data);
  };

  const fetchMsgs = async () => {
    const resp = await apiFetch(`mensajes_ticket_listar&ticket_id=${ticketId}`);
    if (resp.success) setMsgs(resp.data || []);
  };

  useEffect(() => {
    let live = true;
    (async () => {
      setLoading(true);
      setError('');
      try {
        await fetchTicket();
        await fetchMsgs();
        if (live) setTimeout(scrollToBottom, 50);
      } catch (e) {
        setError(e?.message || 'No se pudo cargar el ticket.');
      } finally {
        if (live) setLoading(false);
      }
    })();

    return () => {
      live = false;
    };
  }, [ticketId]);

  useEffect(() => {
    if (!ticketId) return;
    const t = setInterval(() => {
      fetchMsgs().catch(() => {});
    }, 4500);
    return () => clearInterval(t);
  }, [ticketId]);

  useEffect(() => {
    scrollToBottom();
  }, [msgs.length]);

  const send = async () => {
    const message = text.trim();
    if (!message) return;
    if (hasInappropriateContent(message)) {
      setChatError('Contenido inapropiado detectado. El mensaje no puede enviarse.');
      return;
    }

    setChatError('');
    setSending(true);
    try {
      const resp = await apiFetch('mensajes_ticket_enviar', {
        method: 'POST',
        body: { ticket_id: ticketId, mensaje: message }
      });
      if (!resp.success) {
        throw new Error(resp.message || 'Error enviando mensaje');
      }
      setText('');
      await fetchMsgs();
    } catch (e) {
      setChatError(e?.message || 'Error de red enviando mensaje.');
    } finally {
      setSending(false);
    }
  };

  const formatDate = (iso) => {
    try {
      return new Date(iso).toLocaleString('es-ES', { dateStyle: 'medium', timeStyle: 'short' });
    } catch {
      return iso;
    }
  };

  const bubbleClass = (m) => {
    const mine = m.autor_user_id === user?.id;
    if (mine) return 'bubble mine';
    if (m.autor_rol === 'admin') return 'bubble admin';
    return 'bubble';
  };

  if (loading) {
    return (
      <div className="page-container animate-fade">
        <div className="loader-container">Cargando detalle...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="page-container animate-fade">
        <div className="alert-error">{error}</div>
        <button className="btn btn-outline mt-4" onClick={() => navigate(-1)}>
          Volver
        </button>
      </div>
    );
  }

  return (
    <div className="page-container animate-fade">
      <div className="ticket-detail-header">
        <div>
          <BlurFade delay={0.06} yOffset={10}>
            <h1 className="page-title">Ticket #{ticket?.id}</h1>
          </BlurFade>
          <BlurFade delay={0.12} yOffset={10}>
            <p className="page-subtitle">{ticket?.asunto}</p>
          </BlurFade>
        </div>
        <button className="btn btn-outline" onClick={() => navigate(-1)}>
          Volver
        </button>
      </div>

      <div className="card" style={{ padding: '1.25rem' }}>
        <p className="text-gray-600 dark:text-gray-200 leading-relaxed">{ticket?.descripcion}</p>
        <div className="ticket-detail-meta">
          <div className="meta-pill">
            <div className="text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</div>
            <div className="mt-1 font-extrabold text-[#003366] dark:text-[#ffcc00]">{ticket?.estado}</div>
          </div>
          <div className="meta-pill">
            <div className="text-xs font-semibold text-gray-400 uppercase tracking-wider">Prioridad</div>
            <div className="mt-1 font-extrabold text-[#003366] dark:text-[#ffcc00]">{ticket?.prioridad}</div>
          </div>
          <div className="meta-pill">
            <div className="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ubicacion</div>
            <div className="mt-1 font-bold text-gray-700 dark:text-gray-200">{ticket?.ubicacion}</div>
          </div>
          <div className="meta-pill">
            <div className="text-xs font-semibold text-gray-400 uppercase tracking-wider">Creado</div>
            <div className="mt-1 font-bold text-gray-700 dark:text-gray-200">{formatDate(ticket?.created_at)}</div>
          </div>
        </div>
      </div>

      <div className="chat-shell">
        <div className="chat-header">
          <div>
            <div className="text-xs font-semibold text-gray-400 uppercase tracking-wider">Chat del ticket</div>
            <div className="text-sm text-gray-600 dark:text-gray-200 mt-1">
              Conversacion entre {user?.rol === 'admin' ? 'soporte' : 'usuario'} y soporte.
            </div>
          </div>
          <button className="btn btn-outline" onClick={() => fetchMsgs()} disabled={sending}>
            Actualizar
          </button>
        </div>

        <div className="chat-body" ref={bodyRef}>
          {msgs.length === 0 ? (
            <div className="text-sm text-gray-500 italic">Aun no hay mensajes. Escribe para iniciar la conversacion.</div>
          ) : (
            msgs.map((m) => (
              <BlurFade key={m.id} delay={0.02} yOffset={6}>
                <div className={bubbleClass(m)}>
                  <div style={{ whiteSpace: 'pre-wrap' }}>{m.mensaje}</div>
                  <div className="bubble-meta">
                    {m.autor_rol === 'admin' ? 'Soporte' : 'Tu'} · {formatDate(m.created_at)}
                  </div>
                </div>
              </BlurFade>
            ))
          )}
        </div>

        <div className="chat-input">
          <input
            value={text}
            onChange={(e) => {
              const value = e.target.value;
              setText(value);
              if (chatError && !hasInappropriateContent(value)) {
                setChatError('');
              }
            }}
            placeholder="Escribe un mensaje..."
            disabled={sending}
            onKeyDown={(e) => {
              if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                send();
              }
            }}
          />
          <button className="btn btn-primary" type="button" onClick={send} disabled={sending}>
            {sending ? 'Enviando...' : 'Enviar'}
          </button>
        </div>
        {chatError && <div className="alert-error" style={{ margin: '0.8rem' }}>{chatError}</div>}
      </div>
    </div>
  );
};

export default TicketDetalle;

