# Overview of this project
This is an hybrid recommender system joining together collaborative filtering and knowledge-based filtering. It is also a result of the Horizon Europe Project e-DIPLOMA (Project number: 101061424, Call: HORIZON-CL2-2021-TRANSFORMATIONS-01, Duration: 01/09/2022 – 31/08/2025). This recommender is meant to work within LMS (it has been developed using Moodle as the selected LMS). This is a moodle plugin that is supposed to work with the recommender system. The recommender system is a separate project that can be found in this repository: [https://github.com/almtav08/recom_server](https://github.com/almtav08/recom_server)

# How to install the block
For installing this plugin you will have to include it under the following url: ```path_to_your_moodle/local/``` whereever the moodle has been installed. After that you will have to go to the moodle site, reload, and follow the isntallation instructions. This plugin is used for two things:
- The first is to tell the recommender system that a user has interacted with a resource.
- The second is to add a new api endpoint to the moodle api to obtain all the logs of the given course.