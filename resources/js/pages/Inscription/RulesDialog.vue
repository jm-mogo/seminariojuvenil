<template>
    <Dialog :open="open" @update:open="$emit('update:open', $event)">
        <DialogContent class="sm:max-w-[80vw] md:max-w-[70vw] lg:max-w-[60vw]">
            <DialogHeader>
                <DialogTitle>Reglamento Seminario Juvenil</DialogTitle>
                <DialogDescription> Por favor, lee atentamente el reglamento antes de aceptar. </DialogDescription>
            </DialogHeader>

            <!-- Scrollable Content Area -->
            <div class="max-h-[65vh] space-y-3 overflow-y-auto rounded-md border p-4 text-sm">
                <p class="mb-2 text-center font-bold">IGLESIA BÍBLICA BAUTISTA DE VISTA ALEGRE<br />REGLAMENTO SEMINARIO JUVENIL</p>

                <p>
                    ¡Qué bendición saber que has considerado asistir a nuestro Seminario Juvenil! Este promete ser de gran bendición para todos los
                    estudiantes.
                </p>
                <p>
                    Nuestra experiencia en programas de este tipo nos ha enseñado la necesidad de tener un reglamento que regule algunos aspectos que
                    contribuyan al orden de este seminario. Por lo cual te pedimos prestar especial atención a todos y cada uno de los artículos del
                    presente reglamento.
                </p>
                <p>Este reglamento entrará en vigencia a partir del momento que eres admitido en el Seminario Juvenil.</p>
                <p>
                    Algunas de las exigencias presentadas obedecen a principios bíblicos de separación, pudor y modestia en el vestir que deben ser
                    observados por los hijos de Dios en todo tiempo.
                </p>

                <p class="mt-3 font-semibold">DE LOS REQUISITOS DE ELEGIBILIDAD</p>
                <ol class="list-decimal space-y-1 pl-6">
                    <li>
                        Solo podrán asistir jóvenes no menores de 14 años y no mayores de 17 años de edad (Los que cumplan 14 años este mismo año,
                        pueden entrar).
                    </li>
                    <li>Tener la entrevista con el encargado del seminario juvenil.</li>
                </ol>

                <p class="mt-3 font-semibold">DE LO INDISPENSABLE</p>
                <ol start="3" class="list-decimal space-y-1 pl-6">
                    <li>Todo estudiante debe traer su propia Biblia (Versión Reina-Valera 1960), lápiz y cuaderno.</li>
                    <li>Todo estudiante deberá traer su propia lencería y artículos de aseo personal (jabón, shampoo, pasta dental, etc.).</li>
                </ol>

                <p class="mt-3 font-semibold">DE LO QUE NO SE PUEDE TRAER</p>
                <ol start="5" class="list-decimal space-y-1 pl-6">
                    <li>Está restringido el uso de equipos electrónicos como reproductores de sonido o video, radios, mp3, etc.</li>
                    <li>Está prohibido traer cartas de póker, baraja española y juegos electrónicos de video.</li>
                    <li>
                        No se podrá traer celulares; de traerlo, será con previa autorización de la directiva del seminario y estará sujeto a un
                        horario específico. (El representante debe notificar *si* el joven traerá el teléfono).
                    </li>
                </ol>

                <p class="mt-3 font-semibold">DE LO QUE NO SE PUEDE HACER</p>
                <ol start="8" class="list-decimal space-y-1 pl-6">
                    <li>
                        Por razones de seguridad, nadie debe salir de la iglesia sin notificar al director del seminario (Ender Blanquicett) o en su
                        defecto al subdirector (Carlos Pimentel).
                    </li>
                    <li>
                        Está prohibido que estén parejas (varón y hembra) a solas en cualquiera de las áreas de la iglesia. De incurrir en esta falta,
                        podrá ser suspendido o expulsado.
                    </li>
                    <li>Será inaceptable el uso de malas palabras y las ofensas de palabras hacia otros estudiantes o líderes del seminario.</li>
                    <li>No es permitido mostrar su desnudez por ningún motivo en ninguna de las áreas del seminario.</li>
                </ol>

                <p class="mt-3 font-semibold">EN LOS DÍAS DE SERVICIO DE PREDICACIÓN</p>
                <ol start="12" class="list-decimal space-y-1 pl-6">
                    <li>
                        Los estudiantes no pueden hacer ningún tipo de alboroto en los momentos que hay servicio de otro ministerio o en los servicios
                        de predicación de la iglesia.
                    </li>
                    <li>Debe mantener una conducta apropiada en todo momento.</li>
                </ol>

                <p class="mt-3 font-semibold">DE LO QUE SE ESPERA</p>
                <ol start="14" class="list-decimal space-y-1 pl-6">
                    <li>
                        Se espera que todos los estudiantes se involucren en todas las actividades organizadas, salvo aquellas justificadas por
                        problemas de salud u otra razón de fuerza mayor.
                    </li>
                    <li>
                        Los dormitorios y baños deben mantenerse aseados y en orden en todo tiempo (Tendrán un tiempo específico para hacer esa
                        limpieza).
                    </li>
                    <li>
                        Cada vez que salgas del cuarto o de otra instalación de la iglesia, deberás cerrar la puerta, apagar las luces y dejar todo
                        ordenado.
                    </li>
                    <li>
                        Se espera que cada joven mantenga respeto con los demás jóvenes y autoridades del programa. Si le falta el respeto a alguna
                        autoridad, puede ser expulsado.
                    </li>
                </ol>

                <p class="mt-3 font-semibold">DE LA VESTIMENTA</p>
                <ol start="18" class="list-decimal space-y-1 pl-6">
                    <li>
                        De manera general, es necesario que cada joven muestre una vestimenta apropiada, que identifique a una señorita y a un
                        caballero, y que cumpla con los estándares de prudencia y santidad visibles en la Palabra de Dios.
                    </li>
                    <li>
                        Toda estudiante debe vestir falda o vestido, por debajo de las rodillas, y los varones deben usar pantalón o monos, y camisas
                        o franelas con manga. Está prohibido el uso de shorts.
                    </li>
                    <li>No está permitido el uso de gorras en horarios de clases.</li>
                </ol>

                <p class="mt-3 font-semibold">VESTIMENTA DE CADA DÍA</p>
                <p class="mt-1 font-medium">Hombres:</p>
                <ol start="21" class="list-decimal space-y-1 pl-6">
                    <li>La forma de vestir los días jueves será con pantalón de vestir, camisa de cuello, corbata y zapatos pulidos.</li>
                    <li>Los días viernes la vestimenta será casual.</li>
                    <li>Los días sábado será deportiva.</li>
                    <li>No pantalones apretados ni rotos.</li>
                    <li>Modas del mundo no serán permitidas. Apariencias mundanas no serán toleradas en ningún joven.</li>
                </ol>
                <p class="mt-2 font-medium">Damas:</p>
                <ol start="26" class="list-decimal space-y-1 pl-6">
                    <li>Siempre tener un aspecto de cabello ordenado, nada de peinados ostentosos.</li>
                    <li>No es permitido el uso de gorras en horario de clases (solo para hacer deportes y salidas al aire libre).</li>
                    <li>
                        Es necesario que cada joven muestre una vestimenta apropiada, que identifique a una señorita, y que cumpla con los estándares
                        de prudencia y santidad visibles en la Palabra de Dios.
                    </li>
                </ol>

                <p class="mt-3 font-semibold">PARA LAS SESIONES DE JUEGOS Y DEPORTES:</p>
                <ol start="29" class="list-decimal space-y-1 pl-6">
                    <li>Las señoritas: Deben vestir con faldas por debajo de las rodillas y franelas no ceñidas al cuerpo.</li>
                    <li>
                        No será aceptable el uso de shorts, faldas por encima de la rodilla, pantalones, minifaldas, lycras, tops y franelas que
                        descubran el cuerpo.
                    </li>
                </ol>

                <p class="mt-3 font-semibold">CAUSALES DE SUSPENSIÓN O EXPULSIÓN</p>
                <ol start="31" class="list-decimal space-y-1 pl-6">
                    <li>Si algún joven golpea a otro estudiante, será expulsado o suspendido. Se estudiará cada caso.</li>
                    <li>Si se sorprende a un joven tomando algo que no es suyo, será causa para su expulsión.</li>
                    <li>
                        Si el joven quebranta las normas frecuentemente, es sancionado y no muestra ningún tipo de cambio o arrepentimiento, será
                        expulsado.
                    </li>
                    <li>Si tiene una conducta inapropiada o sospechosa con el sexo opuesto, será expulsado.</li>
                    <li>Si algún joven le falta el respeto a uno de los líderes, será considerado para expulsión.</li>
                </ol>

                <p class="mt-3 font-semibold">TRATO ENTRE TODOS Y ALGO MÁS</p>
                <ol start="36" class="list-decimal space-y-1 pl-6">
                    <li>No se permite llamar por sobrenombres a ningún integrante del seminario juvenil.</li>
                    <li>No son permitidas las rencillas, peleas y enemistades con ningún integrante del seminario juvenil.</li>
                </ol>

                <p class="mt-4">
                    Faltas por enfermedad o imprevistos de causa mayor son justificables, pero deben ser notificadas al encargado del Seminario
                    Juvenil (Ender Blanquicett).
                </p>
            </div>

            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" variant="secondary"> Cerrar </Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

// Define props to control the dialog visibility from the parent
defineProps<{
    open: boolean;
}>();

// Define emits to notify the parent when the dialog should close
defineEmits<{
    (e: 'update:open', value: boolean): void;
}>();
</script>
