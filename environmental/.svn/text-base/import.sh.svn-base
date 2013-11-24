#!/bin/bash
# Update the xml data nodes
nice php sapphire/cli-script.php "XmlImportTask/files/\
Sites_Weather|Sites_Weather.xml=sites,\
Sites_Weather|Data_Weather.xml=siteReadings,\
Sites_SwimMarine|Sites_SwimMarine.xml=sites,\
Sites_SwimMarine|Data_SwimMarine.xml=siteReadings,\
Sites_SwimFresh|Sites_SwimFresh.xml=sites,\
Sites_SwimFresh|Data_SwimFresh.xml=siteReadings,\
Sites_Riverflow|Sites_RiverFlow.xml=sites,\
Sites_Riverflow|Data_RiverFlow_Flow7day.xml=siteReadings,\
Sites_Rainfall|Sites_Rainfall.xml=sites,\
Sites_Rainfall|Data_Rainfall.xml=siteReadings,\
Sites_MetBuoy|Sites_MetBuoy.xml=sites,\
Sites_MetBuoy|Data_MetBuoy.xml=siteReadings,\
Sites_Groundwater|Sites_Groundwater.xml=sites,\
Sites_Groundwater|Data_Groundwater.xml=siteReadings,\
Sites_AirQuality|Sites_AirQuality.xml=sites,\
Sites_AirQuality|Data_AirQuality.xml=siteReadings,\
Sites_CatchmentStudy|Sites_CatchmentStudy.xml=sites,\
Sites_CatchmentStudy|Data_CatchmentStudy.xml=siteReadings,\
stagereport.xml=analysis"

# Clean up the old data
nice php sapphire/cli-script.php "XmlImportTask/wipe/\
Weather=30,\
Swim Marine=2009-09-01,\
Swim Fresh=2009-09-01,\
RiverFlow=30,\
Rainfall=30,\
Metbuoy=30,\
Groundwater=30,\
Catchment Study=30,\
Air Quality=30"

# Update the titles and url segments on pages that need it
nice php sapphire/cli-script.php "XmlImportTask/updateTitles"
