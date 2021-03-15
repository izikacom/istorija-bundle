# Enabling data collector

Decorate `CommandBus` & `EventBus` with their Traceable version.

Then configure the `DataCollector`

```xml
<service id="Dayuse\IstorijaBundle\DataCollector\IstorijaDataCollector" public="false">
    <tag name="data_collector" template="@Istorija/Collector/default.html.twig" id="istorija" />
</service>
```