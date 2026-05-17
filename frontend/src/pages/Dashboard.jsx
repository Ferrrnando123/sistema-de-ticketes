import { useEffect, useMemo, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { Link } from 'react-router-dom';
import { Ticket, PlusCircle, HelpCircle } from 'lucide-react';
import { BlurFade } from '../components/magicui/blur-fade';
import { Marquee } from '../components/magicui/marquee';
import { cn } from '../lib/utils';
import { apiFetch } from '../services/api';
import './Dashboard.css';

const timeAgo = (iso) => {
  try {
    const d = new Date(iso).getTime();
    const diff = Date.now() - d;
    const min = Math.max(1, Math.floor(diff / 60000));
    if (min < 60) return `Hace ${min} min`;
    const h = Math.floor(min / 60);
    if (h < 24) return `Hace ${h} h`;
    const days = Math.floor(h / 24);
    return `Hace ${days} d`;
  } catch {
    return '';
  }
};

const avatarFor = (email) => {
  const safe = (email || 'user').split('@')[0].replace(/[^a-z0-9_]/gi, '');
  return `https://avatar.vercel.sh/${safe}`;
};

const ReportCard = ({ email, asunto, created_at, prioridad, estado, id, canOpen }) => {
  const username = email ? `@${email.split('@')[0]}` : '@usuario';
  const userLabel = email ? email.split('@')[0] : 'Usuario';
  return (
    <figure
      className={cn(
        'action-card',
        'relative w-80 min-h-[180px] cursor-pointer items-center text-center mx-4 my-2 p-6 shadow-md',
        'bg-white/50 dark:bg-zinc-900/50 hover:bg-white/80 dark:hover:bg-zinc-900/80'
      )}
    >
      <div className="flex flex-col items-center gap-2 mb-3">
        <img
          className="rounded-full border-2 border-[#003366]/10 dark:border-[#ffcc00]/20"
          width="48"
          height="48"
          alt=""
          src={avatarFor(email)}
        />
        <div className="flex flex-col items-center">
          <figcaption className="text-base font-bold text-[#003366] dark:text-[#ffcc00] leading-tight">
            {userLabel}
          </figcaption>
          <p className="text-[9px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{username}</p>
        </div>
      </div>
      <blockquote className="text-sm leading-relaxed text-gray-600 dark:text-gray-300 font-medium italic mt-auto px-1">
        "{asunto}"
      </blockquote>
      <div className="mt-3 flex items-center justify-center gap-2">
        <div className="text-[9px] font-bold text-gray-400 bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded-md inline-block">
          {timeAgo(created_at)}
        </div>
        <div className="text-[9px] font-extrabold text-[#003366] dark:text-[#ffcc00] bg-white/70 dark:bg-zinc-900/70 px-2 py-1 rounded-md inline-block border border-white/10">
          {prioridad} · {estado}
        </div>
      </div>
      <div className="mt-3">
        {canOpen ? (
          <Link
            to={`/tickets/${id}`}
            className="text-[10px] font-extrabold text-[#003366] dark:text-[#ffcc00] underline underline-offset-4"
          >
            Abrir detalle
          </Link>
        ) : (
          <span className="text-[10px] font-semibold text-gray-400">Solo vista pública</span>
        )}
      </div>
    </figure>
  );
};

const Dashboard = () => {
  const { user } = useAuth();
  const [recent, setRecent] = useState([]);

  useEffect(() => {
    (async () => {
      try {
        const resp = await apiFetch('get_recent_tickets');
        if (resp.success) setRecent(resp.data || []);
      } catch {
        setRecent([]);
      }
    })();
  }, []);

  const [firstRow, secondRow] = useMemo(() => {
    const list = recent.length ? recent : [];
    const mid = Math.ceil(list.length / 2);
    return [list.slice(0, mid), list.slice(mid)];
  }, [recent]);

  return (
    <div className="dashboard-container">
      <header className="page-header" style={{ paddingBottom: '1rem', borderBottom: 'none' }}>
        <BlurFade delay={0.15} yOffset={12}>
          <h1 className="text-3xl md:text-4xl font-extrabold text-[#003366] tracking-tight mb-2">
            Bienvenido, <span className="text-[#003366] font-black">{user?.nombre?.split(' ')[0]}</span>
          </h1>
        </BlurFade>
        <BlurFade delay={0.3} yOffset={12}>
          <p className="text-gray-500 font-medium text-lg">¿En qué podemos ayudarte el día de hoy?</p>
        </BlurFade>
      </header>

      <div className="action-cards">
        <Link to="/nuevo-ticket" className="action-card primary">
          <div className="card-icon">
            <PlusCircle size={32} />
          </div>
          <h3>Crear Ticket</h3>
          <p>Abre una nueva solicitud de soporte institucional.</p>
        </Link>

        <Link to="/mis-tickets" className="action-card">
          <div className="card-icon blue">
            <Ticket size={32} />
          </div>
          <h3>Mis Tickets</h3>
          <p>Consulta el estado de tus solicitudes enviadas.</p>
        </Link>

        <Link to="/ayuda" className="action-card">
          <div className="card-icon green">
            <HelpCircle size={32} />
          </div>
          <h3>Centro de Ayuda</h3>
          <p>Guías y respuestas a preguntas frecuentes.</p>
        </Link>
      </div>

      <div className="mt-64 pb-12 w-full max-w-[1200px] mx-auto px-4 lg:px-0">
        <h2 className="text-xl font-bold text-[#003366] dark:text-[#ffcc00] mb-8 pl-2 tracking-tight">
          Actividad Reciente del Soporte
        </h2>
        <div className="relative flex w-full flex-col items-center justify-center overflow-hidden rounded-xl gap-10">
          {recent.length === 0 ? (
            <div className="text-sm text-gray-400 italic py-8">Sin actividad reciente para mostrar.</div>
          ) : (
            <>
              <Marquee pauseOnHover className="[--duration:40s] [--gap:2rem]">
                {firstRow.map((t) => (
                  <ReportCard
                    key={t.id}
                    {...t}
                    canOpen={user?.rol === 'admin' || t.user_id === user?.id}
                  />
                ))}
              </Marquee>
              <Marquee reverse pauseOnHover className="[--duration:45s] [--gap:2rem]">
                {secondRow.map((t) => (
                  <ReportCard
                    key={t.id}
                    {...t}
                    canOpen={user?.rol === 'admin' || t.user_id === user?.id}
                  />
                ))}
              </Marquee>

              <div className="pointer-events-none absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-[#f4f6f8] dark:from-[#0f172a]"></div>
              <div className="pointer-events-none absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-[#f4f6f8] dark:from-[#0f172a]"></div>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
