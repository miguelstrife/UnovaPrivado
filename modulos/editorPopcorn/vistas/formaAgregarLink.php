<div id="dialog-form-link" title="Agregar un link"  style="display:none;">
    <div id="linkTabs">
         <ul>
            <li><a href="#linkTab">Página web</a></li>
            <li><a href="#tiempoTab">Tiempo</a></li>
            <li><a href="#colorFondoTab">Color de fondo</a></li>
        </ul>
        
        <div id="linkTab">
            <label>Texto: </label><br>
            <input type="text" name="url" id="textoLink" class="text ui-widget-content ui-corner-all"  style="width:97%;"/>
            <br><br>
            <label>Link a la página:</label><br>
            <input type="text" name="url" id="urlLink" class="text ui-widget-content ui-corner-all"  style="width:97%;"/>
        </div>

        
        <div id="tiempoTab">
            <table>
                <tr>
                    <td><label for="Tiempo Inicial">Tiempo inicial</label></td>
                    <td><input type="text" name="tiempoInicio" id="tiempoInicioLink" class="text ui-widget-content ui-corner-all" style="width:40px;"/><br></td>
                </tr>
                <tr>
                    <td><label for="Tiempo Final">Tiempo final</label></td>
                    <td><input type="text" name="tiempoFin" id="tiempoFinLink" class="text ui-widget-content ui-corner-all" style="width:40px;"/></td>
                </tr> 
            </table>
            <div id="tiempoRangeSliderLink"></div>
        </div>
        
        <div id="colorFondoTab">
            <div id="colorSelectorLink" ></div>
            <input type="hidden" name="colorHidden" id="colorHiddenLink"/>
            <br><br>
            <label>Color seleccionado:</label>
            <div id="colorSeleccionadoLink" class="colorButton"></div>
        </div>

    </div>
</div>	