# EstacionamientoMedido
Requisitos:
RF-001: Registro de inspectores
• Descripción: El sistema debe permitir a los administradores registrar inspectores con un
usuario, contraseña y número de identificación único.
• Criterios de aceptación:
• Solo los administradores pueden crear cuentas de inspectores.
• El sistema valida que el número de identificación sea único.
• Prioridad: Alta
RF-002: Inicio de sesión de inspectores
• Descripción: El sistema debe permitir a los inspectores iniciar sesión en una app o
dispositivo móvil con su usuario y contraseña.
• Criterios de aceptación:
• El sistema autentica al inspector en menos de 3 segundos.
• El sistema bloquea la cuenta tras 5 intentos fallidos de inicio de sesión.
• Prioridad: Alta

RF-003: Pago mediante código QR en postes
• Descripción: El sistema debe permitir a los conductores iniciar una sesión de
estacionamiento escaneando un código QR ubicado en un poste frente a cada zona de
estacionamiento, que redirige a una página de pago.
• Criterios de aceptación:
• El código QR es único por cada espacio de estacionamiento y está asociado a una
zona regulada.
• La página de pago permite seleccionar el tiempo de estacionamiento (en
incrementos de, por ejemplo, 30 minutos, 1 hora, 2 horas).
• La página de pago acepta tarjeta de crédito/débito o transferencia bancaria.
• El pago se confirma en menos de 10 segundos, y la sesión de estacionamiento se
activa automáticamente.

• No requiere inicio de sesión para pagos únicos, pero ofrece la opción de vincular el
pago a una cuenta registrada.
Gestión de Sesiones de Estacionamiento
• RF-004: Inicio de sesión de estacionamiento
• Descripción: El sistema debe permitir a los conductores iniciar una sesión de
estacionamiento escaneando un código QR en un poste que identifica el espacio
de estacionamiento.
• Criterios de aceptación:
• Si se usa el QR, el sistema asocia automáticamente la zona de
estacionamiento al pago.
• El sistema verifica que el horario actual esté dentro del horario tarifado (lunes
a viernes, 8 a 20 h).
• La sesión se inicia en menos de 5 segundos tras el pago o confirmación.

RF-005: Finalización de sesión de estacionamiento.
• Descripción: El sistema debe permitir a los conductores finalizar una sesión de
estacionamiento automáticamente al finalizar el tiempo pagado mediante el código QR.
• Criterios de aceptación:
• la sesión finaliza automáticamente al agotarse el tiempo seleccionado.
• La finalización se registra en menos de 5 segundos.
• Prioridad: Alta
RF-006: Cálculo automático de costos ().
• Descripción: El sistema debe calcular automáticamente el costo de la sesión de
estacionamiento según el tiempo seleccionado en el pago por QR, dentro del horario
tarifado.
• Criterios de aceptación:
• Para pagos por QR, el costo se basa en el tiempo predefinido seleccionado
(ejemplo: $X por 1 hora).
• No se cobra fuera del horario tarifado.
• Prioridad: Alta
RF-007: Consulta de sesiones activas
• Descripción: El sistema debe permitir a los conductores consultar las sesiones de
estacionamiento activas asociadas a su cuenta.
• Criterios de aceptación:
• La app muestra la patente, hora de inicio y tiempo transcurrido de la sesión activa.
• La consulta se realiza en menos de 3 segundos.
• Prioridad: Media

RF-008: Consulta de estacionamiento activo por inspectores
• Descripción: El sistema debe permitir a los inspectores consultar en tiempo real, mediante
una app móvil, todos los espacios de estacionamiento en una zona regulada, indicando
cuáles tienen sesiones activas.
• Criterios de aceptación:
• La app muestra una lista o mapa de los espacios de estacionamiento, con su
estado (activo, inactivo).

• Si no hay sesión activa, el sistema indica si el vehículo está dentro del horario
tarifado.
• Prioridad: Alta
RF-009: Registro de infracciones
• Descripción: El sistema debe permitir a los inspectores registrar una infracción si un
vehículo no tiene una sesión activa dentro del horario tarifado.
• Criterios de aceptación:
• El sistema registra la patente, fecha, hora, ubicación (opcional) y código del
inspector.
• El sistema genera un identificador único para la infracción.
• La infracción se registra en menos de 10 segundos.
• Prioridad: Alta
RF-010: Notificación de infracciones
• Descripción: El sistema debe notificar al conductor sobre una infracción registrada
asociada a su vehículo.
• Criterios de aceptación:
• La notificación se envía por correo electrónico o notificación push en un plazo de 24
horas.
• La notificación incluye detalles de la infracción (patente, fecha, hora, ubicación).
• Prioridad: Media
5. Administración y Estadísticas
• RF-011: Consulta de estadísticas generales
• Descripción: El sistema debe permitir a los operadores consultar estadísticas
generales, como cantidad de usuarios activos, recaudación total e infracciones
registradas.
• Criterios de aceptación:
• Las estadísticas se pueden filtrar por período (diario, semanal, mensual).
• Los resultados se muestran en menos de 5 segundos.
• Los datos se pueden exportar en formato CSV o PDF.
• Prioridad: Media
• RF-012: Gestión de tarifas
• Descripción: El sistema debe permitir a los administradores configurar y modificar
las tarifas por hora de estacionamiento.
• Criterios de aceptación:
• Solo los administradores autenticados pueden modificar las tarifas.
• Los cambios se aplican a nuevas sesiones en un plazo de 1 hora.
• Prioridad: Media
• RF-013: Gestión de zonas reguladas
• Descripción: El sistema debe permitir a los administradores definir las zonas
reguladas de estacionamiento y sus horarios tarifados.
• Criterios de aceptación:
• Las zonas se pueden especificar por coordenadas geográficas o nombres de
calles.
• Los horarios tarifados son configurables por día de la semana.
• Prioridad: Alta
