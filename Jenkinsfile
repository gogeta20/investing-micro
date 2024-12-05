pipeline {
    agent any
    stages {
        stage('Setup Backend Symfony') {
            steps {
                dir('project/backend/symfony') {
                    sh 'composer install'
                    sh 'php bin/console cache:clear --env=test'
                }
            }
        }
        stage('Setup Backend Django') {
            steps {
                dir('project/backend/django') {
                    sh 'pip install -r requirements.txt'
                }
            }
        }
        stage('Build Spring Boot') {
            steps {
                dir('project/backend/springboot') {
                    sh './mvnw clean package'
                }
            }
        }
        stage('Setup Frontend') {
            steps {
                dir('project/front') {
                    sh 'pnpm install'
                    sh 'pnpm build'
                }
            }
        }
    }
    post {
        always {
            archiveArtifacts artifacts: '**/target/*.jar', fingerprint: true
        }
        success {
            echo 'Build and tests completed successfully!'
        }
        failure {
            echo 'Build or tests failed.'
        }
    }
}
