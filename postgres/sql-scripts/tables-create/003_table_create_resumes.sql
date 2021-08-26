CREATE TABLE resumes
(
	id                                      INT                                 NOT NULL        GENERATED BY DEFAULT AS IDENTITY(MINVALUE 0 START WITH 0 INCREMENT BY 1),
	full_name                               VARCHAR(255)                        NOT NULL,
	about                                   TEXT,
	work_experience                         INT                                 NOT NULL		DEFAULT 0,
	desired_salary                          DOUBLE PRECISION                    NOT NULL,
	birth_date                              DATE                                NOT NULL,
	sending_datetime                        TIMESTAMP                           NOT NULL		DEFAULT NOW(),
	city_to_work_in_id                      INT                                 NOT NULL		DEFAULT 0,
	desired_vacancy_id                      INT                                 NOT NULL		DEFAULT 0,
	avatar                                  VARCHAR(64)             			DEFAULT 'На данный момент не знаю как реализовать',
	file                                    VARCHAR(64)                         DEFAULT 'На данный момент не знаю как реализовать',
	CONSTRAINT pkey_resumes_id	            PRIMARY KEY(id),
	CONSTRAINT ukey_resumes_full_name	    UNIQUE(full_name),
    CONSTRAINT fkey_resumes_to_cities       FOREIGN KEY(city_to_work_in_id)     REFERENCES cities(id),
    CONSTRAINT fkey_resumes_to_vacancies    FOREIGN KEY(desired_vacancy_id)     REFERENCES vacancies(id)
);