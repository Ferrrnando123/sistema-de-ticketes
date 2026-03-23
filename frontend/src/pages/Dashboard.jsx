import { useAuth } from '../context/AuthContext';
import { Link } from 'react-router-dom';
import { Ticket, PlusCircle, HelpCircle } from 'lucide-react';
import { BlurFade } from '../components/magicui/blur-fade';
import { Marquee } from '../components/magicui/marquee';
import { cn } from '../lib/utils';
import './Dashboard.css';

const recentReports = [
  { id: 1, title: "Falla de proyector en Lab 3", user: "Carlos M.", username: "@carlos_m", time: "Hace 5 min", img: "https://avatar.vercel.sh/carlos" },
  { id: 2, title: "Problema de red local en Edificio B", user: "Ana López", username: "@ana_it", time: "Hace 12 min", img: "https://avatar.vercel.sh/ana" },
  { id: 3, title: "Impresora atascada en Biblioteca", user: "Luis G.", username: "@luis_bib", time: "Hace 25 min", img: "https://avatar.vercel.sh/luis" },
  { id: 4, title: "Actualización de software requerida", user: "María F.", username: "@maria_dev", time: "Hace 1 hora", img: "https://avatar.vercel.sh/maria" },
  { id: 5, title: "Mantenimiento de aire acondicionado", user: "Soporte", username: "@soporte_udb", time: "Hace 2 horas", img: "https://avatar.vercel.sh/soporte" },
  { id: 6, title: "Error en portal de alumnos", user: "Jose R.", username: "@jose_est", time: "Hace 3 horas", img: "https://avatar.vercel.sh/jose" },
];

const firstRow = recentReports.slice(0, recentReports.length / 2);
const secondRow = recentReports.slice(recentReports.length / 2);

const ReportCard = ({ user, username, title, time, img }) => {
  return (
    <figure
      className={cn(
        "action-card",
        "relative w-80 min-h-[180px] cursor-pointer items-center text-center mx-4 my-2 p-6 shadow-md",
        "bg-white/50 dark:bg-zinc-900/50 hover:bg-white/80 dark:hover:bg-zinc-900/80"
      )}
    >
      <div className="flex flex-col items-center gap-2 mb-3">
        <img className="rounded-full border-2 border-[#003366]/10 dark:border-[#ffcc00]/20" width="48" height="48" alt="" src={img} />
        <div className="flex flex-col items-center">
          <figcaption className="text-base font-bold text-[#003366] dark:text-[#ffcc00] leading-tight">
            {user}
          </figcaption>
          <p className="text-[9px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{username}</p>
        </div>
      </div>
      <blockquote className="text-sm leading-relaxed text-gray-600 dark:text-gray-300 font-medium italic mt-auto px-1">
        "{title}"
      </blockquote>
      <div className="mt-3 text-[9px] font-bold text-gray-400 bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded-md inline-block">
        {time}
      </div>
    </figure>
  );
};

const Dashboard = () => {
  const { user } = useAuth();

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
          <div className="card-icon"><PlusCircle size={32} /></div>
          <h3>Crear Ticket</h3>
          <p>Abre una nueva solicitud de soporte institucional.</p>
        </Link>

        <Link to="/mis-tickets" className="action-card">
          <div className="card-icon blue"><Ticket size={32} /></div>
          <h3>Mis Tickets</h3>
          <p>Consulta el estado de tus solicitudes enviadas.</p>
        </Link>

        <Link to="/ayuda" className="action-card">
          <div className="card-icon green"><HelpCircle size={32} /></div>
          <h3>Centro de Ayuda</h3>
          <p>Guías y respuestas a preguntas frecuentes.</p>
        </Link>
      </div>

      <div className="mt-64 pb-12 w-full max-w-[1200px] mx-auto px-4 lg:px-0">
        <h2 className="text-xl font-bold text-[#003366] dark:text-[#ffcc00] mb-8 pl-2 tracking-tight">
          Actividad Reciente del Soporte
        </h2>
        <div className="relative flex w-full flex-col items-center justify-center overflow-hidden rounded-xl">
          <Marquee pauseOnHover className="[--duration:40s] [--gap:2rem]">
            {firstRow.map((report) => (
              <ReportCard key={report.id} {...report} />
            ))}
          </Marquee>
          <Marquee reverse pauseOnHover className="[--duration:45s] [--gap:2rem]">
            {secondRow.map((report) => (
              <ReportCard key={report.id} {...report} />
            ))}
          </Marquee>

          {/* Sombras laterales para el efecto de desvanecimiento */}
          <div className="pointer-events-none absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-[#f4f6f8] dark:from-[#0f172a]"></div>
          <div className="pointer-events-none absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-[#f4f6f8] dark:from-[#0f172a]"></div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
