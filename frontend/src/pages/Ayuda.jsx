const Ayuda = () => {
  return (
    <div className="page-container animate-fade">
      <h1 className="page-title">Centro de Ayuda</h1>
      <p className="page-subtitle">Respuestas a las preguntas más comunes sobre el sistema.</p>
      
      <div className="card">
        <h3>¿Cómo abro un nuevo ticket?</h3>
        <p style={{marginBottom: '1.5rem', marginTop: '0.5rem', color: 'var(--secondary-color)'}}>
          Ve a la pestaña <strong>Nuevo Ticket</strong> en el menú lateral. Completa los detalles del formulario con la mayor exactitud posible y haz clic en crear.
        </p>

        <h3>¿Puedo cambiar mi contraseña aquí?</h3>
        <p style={{marginBottom: '1.5rem', marginTop: '0.5rem', color: 'var(--secondary-color)'}}>
          No. La cuenta usada es tu cuenta institucional (UDB / Alumno UDB). Si tienes problemas con tus credenciales, contacta a Soporte Técnico presencialmente.
        </p>

        <h3>¿Qué significan los estados del ticket?</h3>
        <ul style={{paddingLeft: '1.5rem', color: 'var(--secondary-color)', marginTop: '0.5rem'}}>
          <li style={{marginBottom: '0.5rem'}}><strong>Abierto:</strong> El ticket fue creado y espera revisión.</li>
          <li style={{marginBottom: '0.5rem'}}><strong>En Progreso:</strong> Un administrador está trabajando en solucionarlo.</li>
          <li style={{marginBottom: '0.5rem'}}><strong>Resuelto:</strong> El inconveniente fue solucionado exitosamente.</li>
        </ul>
      </div>
    </div>
  );
};

export default Ayuda;
