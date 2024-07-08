# Overview of this project
This is an hybrid recommender system joining together collaborative filtering and knowledge-based filtering. It is also a result of the Horizon Europe Project e-DIPLOMA (Project number: 101061424, Call: HORIZON-CL2-2021-TRANSFORMATIONS-01, Duration: 01/09/2022 – 31/08/2025). This recommender is meant to work within LMS (it has been developed using Moodle as the selected LMS). This is a moodle plugin that is supposed to work with the recommender system. The recommender system is a separate project that can be found in this repository: [https://github.com/almtav08/recom_server](https://github.com/almtav08/recom_server)

# How to install the plugin
For installing this plugin you will have to include it under the following url: ```path_to_your_moodle/local/``` wherever the moodle has been installed. After that you will have to go to the moodle site, reload, and follow the installation instructions. This plugin has only one configuration setting, which is the URL of the recommender system. This URL should be the same as the one used in the recommender system.

# How to use the plugin
This plugin has two usage scenarios:
1. **Create logs of user interaction**: This tells the server a student has interacted with a resource. This is done automatically.
2. **Create endpoints**: This creates two endpoints that will be used at the moment of creating the recommender. The first is about getting the course modules content of a given course, and the second is about getting the user interactions with the course modules.